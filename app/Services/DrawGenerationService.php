<?php

namespace App\Services;

use App\Models\Athlete;
use App\Models\Draw;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class DrawGenerationService
{
    /**
     * Generate or regenerate a draw for a specific category within an event.
     * Persists to the database via Draw::updateOrCreate.
     */
    public function generate(Event $event, string $ageCategory, string $gender, string $weightCategory): Draw
    {
        $athletes = Athlete::forEvent($event->id)
            ->validated()
            ->forCategory($ageCategory, $gender, $weightCategory)
            ->orderBy('last_name')
            ->get()
            ->shuffle()
            ->values();

        $count = $athletes->count();
        if ($count < 2) {
            throw new \RuntimeException(
                "Impossible de générer un tirage avec moins de 2 athlètes ({$count} trouvé(s))."
            );
        }

        $usePools = $count >= 6;
        $result   = $usePools
            ? $this->generatePoolElimination($athletes->all())
            : $this->generateDirectElimination($athletes->all());

        return Draw::updateOrCreate(
            [
                'event_id'        => $event->id,
                'age_category'    => $ageCategory,
                'gender'          => $gender,
                'weight_category' => $weightCategory,
            ],
            [
                'category'        => (new WeightCategoryService())->buildCategoryString($ageCategory, $gender, $weightCategory),
                'total_athletes'  => $count,
                'use_pools'       => $usePools,
                'matches'         => $result['matches'] ?? null,
                'pools'           => $result['pools'] ?? null,
                'generated_by'    => Auth::id(),
                'generated_at'    => now(),
            ]
        );
    }

    // ── Direct single-elimination ─────────────────────────────────────────────
    // Public so unit tests can exercise bracket logic without hitting the DB.

    public function generateDirectElimination(array $athletes): array
    {
        $count       = count($athletes);
        $bracketSize = $this->nextPowerOfTwo($count);
        $byes        = $bracketSize - $count;

        // Seed: real athletes + BYE slots
        $seeded = array_values($athletes);
        for ($i = 0; $i < $byes; $i++) {
            $seeded[] = null;
        }

        $matches  = [];
        $matchNum = 1;
        $rounds   = (int) log2($bracketSize);

        // Round 1: pair up seeds
        $round1 = [];
        for ($i = 0; $i < $bracketSize; $i += 2) {
            $a1    = $seeded[$i] ?? null;
            $a2    = $seeded[$i + 1] ?? null;
            $isBye = ($a1 === null || $a2 === null);
            $winner = $isBye ? ($a1 ?? $a2) : null;

            $match = [
                'id'        => $matchNum,
                'round'     => $rounds,
                'position'  => ($i / 2) + 1,
                'athlete1'  => $a1 ? $this->toArray($a1) : null,
                'athlete2'  => $a2 ? $this->toArray($a2) : null,
                'winner'    => $winner ? $this->toArray($winner) : null,
                'winner_id' => $winner ? ($winner->id ?? $winner['id'] ?? null) : null,
                'is_bye'    => $isBye,
                'pool'      => null,
            ];

            $matches[] = $match;
            $round1[]  = $match;
            $matchNum++;
        }

        // Subsequent rounds (empty initially, winners fill in)
        $prevRound = $round1;
        for ($r = $rounds - 1; $r >= 1; $r--) {
            $nextRound = [];
            for ($i = 0; $i < count($prevRound); $i += 2) {
                $m1 = $prevRound[$i];
                $m2 = $prevRound[$i + 1] ?? null;

                $a1    = $m1['winner'] ?? null;
                $a2    = $m2['winner'] ?? null;
                $isBye = ($a1 !== null && $a2 === null) || ($a1 === null && $a2 !== null);
                $winner = $isBye ? ($a1 ?? $a2) : null;

                $match = [
                    'id'        => $matchNum,
                    'round'     => $r,
                    'position'  => ($i / 2) + 1,
                    'athlete1'  => $a1,
                    'athlete2'  => $a2,
                    'winner'    => $winner,
                    'winner_id' => $winner ? ($winner['id'] ?? null) : null,
                    'is_bye'    => $isBye,
                    'pool'      => null,
                ];

                $matches[]   = $match;
                $nextRound[] = $match;
                $matchNum++;
            }
            $prevRound = $nextRound;
        }

        return [
            'format'  => 'direct_elimination',
            'matches' => $matches,
            'pools'   => null,
        ];
    }

    // ── Pool + direct elimination ─────────────────────────────────────────────

    public function generatePoolElimination(array $athletes): array
    {
        $count    = count($athletes);
        $numPools = $count <= 8 ? 2 : ($count <= 12 ? 3 : 4);

        // Snake distribution for balanced pools
        $poolAthletes = array_fill(0, $numPools, []);
        foreach (array_values($athletes) as $idx => $athlete) {
            $col     = $idx % $numPools;
            $row     = intdiv($idx, $numPools);
            $poolIdx = ($row % 2 === 0) ? $col : ($numPools - 1 - $col);
            $poolAthletes[$poolIdx][] = $athlete;
        }

        $matchNum = 1;
        $pools    = [];

        foreach ($poolAthletes as $pi => $group) {
            $poolName = 'Poule ' . chr(65 + $pi);
            $poolMatches = [];
            $n = count($group);

            for ($i = 0; $i < $n - 1; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    $poolMatches[] = [
                        'id'        => $matchNum++,
                        'round'     => 1,
                        'position'  => count($poolMatches) + 1,
                        'athlete1'  => $this->toArray($group[$i]),
                        'athlete2'  => $this->toArray($group[$j]),
                        'winner'    => null,
                        'winner_id' => null,
                        'is_bye'    => false,
                        'pool'      => $poolName,
                    ];
                }
            }

            $pools[] = [
                'name'      => $poolName,
                'athletes'  => array_map([$this, 'toArray'], $group),
                'matches'   => $poolMatches,
                'winner'    => null,
                'runner_up' => null,
            ];
        }

        // Finals bracket (placeholders — filled when pool matches complete)
        $finalsMatches = [];
        for ($i = 0; $i < min($numPools, 2); $i++) {
            $other = chr(65 + (($i + 1) % $numPools));
            $finalsMatches[] = [
                'id'        => $matchNum++,
                'round'     => 2,
                'position'  => $i + 1,
                'athlete1'  => ['name' => '1er Poule ' . chr(65 + $i), 'placeholder' => true],
                'athlete2'  => ['name' => "2ème Poule {$other}", 'placeholder' => true],
                'winner'    => null,
                'winner_id' => null,
                'is_bye'    => false,
                'pool'      => 'DEMI-FINALE',
            ];
        }
        $finalsMatches[] = [
            'id'        => $matchNum,
            'round'     => 3,
            'position'  => 1,
            'athlete1'  => ['name' => 'Vainqueur Demi 1', 'placeholder' => true],
            'athlete2'  => ['name' => 'Vainqueur Demi 2', 'placeholder' => true],
            'winner'    => null,
            'winner_id' => null,
            'is_bye'    => false,
            'pool'      => 'FINALE',
        ];

        return [
            'format'  => 'pool_elimination',
            'matches' => null,
            'pools'   => ['pools' => $pools, 'finals' => $finalsMatches],
        ];
    }

    // ── Winner management ─────────────────────────────────────────────────────

    /**
     * Set winner on a Draw model and persist it.
     */
    public function setMatchWinner(Draw $draw, int $matchId, int $athleteId): Draw
    {
        if ($draw->use_pools && $draw->pools) {
            $pools = $draw->pools;
            foreach ($pools['pools'] as &$pool) {
                foreach ($pool['matches'] as &$match) {
                    if ((int) $match['id'] === $matchId) {
                        $candidates = array_filter([$match['athlete1'], $match['athlete2']]);
                        $winner = collect($candidates)->firstWhere('id', $athleteId);
                        $match['winner']    = $winner;
                        $match['winner_id'] = $athleteId;
                    }
                }
                $pool = $this->calculatePoolStandings($pool);
            }
            $draw->pools = $pools;
        } else {
            $matches = $draw->matches;
            foreach ($matches as &$match) {
                if ((int) $match['id'] === $matchId) {
                    $candidates = array_filter([$match['athlete1'], $match['athlete2']]);
                    $winner = collect($candidates)->firstWhere('id', $athleteId);
                    $match['winner']    = $winner;
                    $match['winner_id'] = $athleteId;
                    $matches = $this->propagateWinner($matches, $match);
                    break;
                }
            }
            $draw->matches = $matches;
        }

        $draw->save();
        return $draw;
    }

    /**
     * Set winner on a raw draw array (for unit testing / in-memory use).
     */
    public function setMatchWinnerInArray(array $draw, int $matchId, int $athleteId): array
    {
        foreach ($draw['matches'] as &$match) {
            if ((int) $match['id'] === $matchId) {
                $candidates = array_filter([$match['athlete1'], $match['athlete2']]);
                $winner = collect($candidates)->firstWhere('id', $athleteId);
                $match['winner']    = $winner;
                $match['winner_id'] = $athleteId;
                $draw['matches'] = $this->propagateWinner($draw['matches'], $match);
                break;
            }
        }
        return $draw;
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function nextPowerOfTwo(int $n): int
    {
        $p = 1;
        while ($p < $n) {
            $p *= 2;
        }
        return $p;
    }

    private function toArray(mixed $athlete): array
    {
        if (is_array($athlete)) {
            return $athlete;
        }
        return [
            'id'       => $athlete->id ?? null,
            'name'     => $athlete->full_name ?: ($athlete->first_name . ' ' . $athlete->last_name),
            'club'     => $athlete->club ?? '',
            'category' => $athlete->category_label ?? '',
            'seed'     => null,
        ];
    }

    private function propagateWinner(array $matches, array $updatedMatch): array
    {
        $targetRound    = $updatedMatch['round'] - 1;
        $targetPosition = (int) ceil($updatedMatch['position'] / 2);
        $isFirst        = $updatedMatch['position'] % 2 !== 0;

        foreach ($matches as &$match) {
            if ($match['round'] === $targetRound && $match['position'] === $targetPosition) {
                if ($isFirst) {
                    $match['athlete1'] = $updatedMatch['winner'];
                } else {
                    $match['athlete2'] = $updatedMatch['winner'];
                }
                // Auto-advance past bye
                if ($match['athlete1'] !== null && $match['athlete2'] === null) {
                    $match['winner']    = $match['athlete1'];
                    $match['winner_id'] = $match['athlete1']['id'] ?? null;
                    $match['is_bye']    = true;
                    $matches = $this->propagateWinner($matches, $match);
                }
                break;
            }
        }

        return $matches;
    }

    private function calculatePoolStandings(array $pool): array
    {
        $wins = array_fill_keys(array_column($pool['athletes'], 'id'), 0);

        foreach ($pool['matches'] as $match) {
            if (! empty($match['winner_id'])) {
                $wins[$match['winner_id']] = ($wins[$match['winner_id']] ?? 0) + 1;
            }
        }

        arsort($wins);
        $ranked = array_keys($wins);

        $pool['winner']    = isset($ranked[0]) ? collect($pool['athletes'])->firstWhere('id', $ranked[0]) : null;
        $pool['runner_up'] = isset($ranked[1]) ? collect($pool['athletes'])->firstWhere('id', $ranked[1]) : null;

        return $pool;
    }
}
