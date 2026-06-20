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
            ->where(function ($q) {
                $q->whereNull('weigh_in_status')->orWhere('weigh_in_status', 'passed');
            })
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

        $usePools = false;
        $result   = $this->generateDirectElimination($athletes->all());

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

        // Distribute byes evenly so no athlete gets more than one consecutive bye.
        // Appending all nulls at the end concentrates byes in one half, creating
        // cascading bye-matches across multiple rounds (16ES→8ES→QF…).
        // buildBalancedSeeds guarantees every 2-slot leaf has ≥1 real athlete,
        // because N > bracketSize/2 always holds when bracketSize = nextPowerOfTwo(N).
        $seeded = $this->buildBalancedSeeds(array_values($athletes), $bracketSize);

        $matches  = [];
        $matchNum = 1;
        $rounds   = (int) log($bracketSize, 2);

        // Round 1: pair up seeds — skip phantom slots (both null = no real athlete ever)
        $round1 = [];
        for ($i = 0; $i < $bracketSize; $i += 2) {
            $a1 = $seeded[$i] ?? null;
            $a2 = $seeded[$i + 1] ?? null;

            $isPhantom = ($a1 === null && $a2 === null);
            $isBye     = !$isPhantom && ($a1 === null || $a2 === null);
            $winner    = $isBye ? ($a1 ?? $a2) : null;

            $entry = [
                'id'        => $isPhantom ? null : $matchNum,
                'round'     => $rounds,
                'position'  => ($i / 2) + 1,
                'athlete1'  => $a1 ? $this->toArray($a1) : null,
                'athlete2'  => $a2 ? $this->toArray($a2) : null,
                'winner'    => $winner ? $this->toArray($winner) : null,
                'winner_id' => $isBye ? ($winner->id ?? null) : null,
                'is_bye'    => $isBye,
                'pool'      => null,
                '_phantom'  => $isPhantom,
            ];

            if (!$isPhantom) {
                $matches[] = array_diff_key($entry, ['_phantom' => true]);
                $matchNum++;
            }
            $round1[] = $entry;
        }

        // Subsequent rounds (empty initially, winners fill in)
        $prevRound = $round1;
        for ($r = $rounds - 1; $r >= 1; $r--) {
            $nextRound = [];
            for ($i = 0; $i < count($prevRound); $i += 2) {
                $m1 = $prevRound[$i];
                $m2 = $prevRound[$i + 1] ?? null;

                // Propagate phantom: only phantom when BOTH feeder slots are phantom
                $p1        = $m1['_phantom'] ?? false;
                $p2        = ($m2 === null) || ($m2['_phantom'] ?? false);
                $isPhantom = $p1 && $p2;

                $a1 = $m1['winner'] ?? null;
                $a2 = $m2 ? ($m2['winner'] ?? null) : null;

                $entry = [
                    'id'        => $isPhantom ? null : $matchNum,
                    'round'     => $r,
                    'position'  => ($i / 2) + 1,
                    'athlete1'  => $a1,
                    'athlete2'  => $a2,
                    'winner'    => null,
                    'winner_id' => null,
                    'is_bye'    => false,
                    'pool'      => null,
                    '_phantom'  => $isPhantom,
                ];

                if (!$isPhantom) {
                    $matches[] = array_diff_key($entry, ['_phantom' => true]);
                    $matchNum++;
                }
                $nextRound[] = $entry;
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
        $raw      = max(2, (int) ceil($count / 4));
        $numPools = $raw;

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

        // Finals bracket: direct elimination of pool qualifiers
        // 2 pools → 4 qualifiers (1st + 2nd from each, cross-bracket)
        // 4+ pools → 1 qualifier per pool, cross-bracket halves
        if ($numPools === 2) {
            $qualifiers = [
                ['id' => null, 'name' => '1er Poule A',  'placeholder' => true, 'club' => '', 'seed' => 'A'],
                ['id' => null, 'name' => '2ème Poule B', 'placeholder' => true, 'club' => '', 'seed' => 'B'],
                ['id' => null, 'name' => '1er Poule B',  'placeholder' => true, 'club' => '', 'seed' => 'B'],
                ['id' => null, 'name' => '2ème Poule A', 'placeholder' => true, 'club' => '', 'seed' => 'A'],
            ];
        } else {
            $half       = (int) floor($numPools / 2);
            $qualifiers = [];
            for ($qi = 0; $qi < $half; $qi++) {
                $l1             = chr(65 + $qi);
                $l2             = chr(65 + $qi + $half);
                $qualifiers[]   = ['id' => null, 'name' => '1er Poule ' . $l1, 'placeholder' => true, 'club' => '', 'seed' => $l1];
                $qualifiers[]   = ['id' => null, 'name' => '1er Poule ' . $l2, 'placeholder' => true, 'club' => '', 'seed' => $l2];
            }
            if ($numPools % 2 === 1) {
                $l             = chr(65 + $numPools - 1);
                $qualifiers[]  = ['id' => null, 'name' => '1er Poule ' . $l, 'placeholder' => true, 'club' => '', 'seed' => $l];
            }
        }

        $nQ        = count($qualifiers);
        $bktSize   = $this->nextPowerOfTwo($nQ);
        $bktByes   = $bktSize - $nQ;
        $seeded    = array_values($qualifiers);
        for ($b = 0; $b < $bktByes; $b++) {
            $seeded[] = null;
        }
        $bktRounds     = (int) log($bktSize, 2);
        $finalsMatches = [];

        // Round 1 of finals bracket
        $round1 = [];
        for ($i = 0; $i < $bktSize; $i += 2) {
            $q1     = $seeded[$i] ?? null;
            $q2     = $seeded[$i + 1] ?? null;
            $isBye  = ($q1 === null || $q2 === null);
            $winner = $isBye ? ($q1 ?? $q2) : null;

            $poolLabel = match (true) {
                $bktRounds >= 3 => 'QUART-DE-FINALE',
                $bktRounds === 2 => 'DEMI-FINALE',
                default         => 'FINALE',
            };

            $match           = ['id' => $matchNum++, 'round' => $bktRounds, 'position' => ($i / 2) + 1,
                                 'athlete1' => $q1, 'athlete2' => $q2, 'winner' => $winner,
                                 'winner_id' => null, 'is_bye' => $isBye, 'pool' => $poolLabel];
            $finalsMatches[] = $match;
            $round1[]        = $match;
        }

        // Subsequent rounds
        $prevRound = $round1;
        for ($r = $bktRounds - 1; $r >= 1; $r--) {
            $nextRound = [];
            for ($i = 0; $i < count($prevRound); $i += 2) {
                $m1     = $prevRound[$i];
                $m2     = $prevRound[$i + 1] ?? null;
                $a1     = $m1['winner'] ?? null;
                $a2     = $m2 ? ($m2['winner'] ?? null) : null;
                $isBye  = ($a1 !== null && $a2 === null) || ($a1 === null && $a2 !== null);
                $winner = $isBye ? ($a1 ?? $a2) : null;

                $poolLabel = match ($r) {
                    1       => 'FINALE',
                    2       => 'DEMI-FINALE',
                    default => 'QUART-DE-FINALE',
                };

                $match           = ['id' => $matchNum++, 'round' => $r, 'position' => ($i / 2) + 1,
                                     'athlete1' => $a1, 'athlete2' => $a2, 'winner' => $winner,
                                     'winner_id' => null, 'is_bye' => $isBye, 'pool' => $poolLabel];
                $finalsMatches[] = $match;
                $nextRound[]     = $match;
            }
            $prevRound = $nextRound;
        }

        // 3rd place match (round = 0, excluded from main bracket tree)
        $finalsMatches[] = [
            'id' => $matchNum, 'round' => 0, 'position' => 1,
            'athlete1'  => ['id' => null, 'name' => 'Perdant SF 1', 'placeholder' => true, 'club' => ''],
            'athlete2'  => ['id' => null, 'name' => 'Perdant SF 2', 'placeholder' => true, 'club' => ''],
            'winner' => null, 'winner_id' => null, 'is_bye' => false, 'pool' => 'PETITE FINALE',
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
            $pools        = $draw->pools;
            $foundInPools = false;

            foreach ($pools['pools'] as &$pool) {
                foreach ($pool['matches'] as &$match) {
                    if ((int) $match['id'] === $matchId) {
                        $candidates         = array_filter([$match['athlete1'], $match['athlete2']]);
                        $winner             = collect($candidates)->firstWhere('id', $athleteId);
                        $match['winner']    = $winner;
                        $match['winner_id'] = $athleteId;
                        $foundInPools       = true;
                    }
                }
                $pool = $this->calculatePoolStandings($pool);
            }
            unset($pool);

            if (! $foundInPools && ! empty($pools['finals'])) {
                $pools['finals'] = $this->setFinalsMatchWinner($pools['finals'], $matchId, $athleteId);
            }

            $draw->pools = $pools;
        } else {
            $matches = $draw->matches;
            foreach ($matches as &$match) {
                if ((int) $match['id'] === $matchId) {
                    $candidates         = array_filter([$match['athlete1'], $match['athlete2']]);
                    $winner             = collect($candidates)->firstWhere('id', $athleteId);
                    $match['winner']    = $winner;
                    $match['winner_id'] = $athleteId;
                    $matches            = $this->propagateWinner($matches, $match);
                    break;
                }
            }
            $draw->matches = $matches;
        }

        $draw->save();
        return $draw;
    }

    private function setFinalsMatchWinner(array $finals, int $matchId, int $athleteId): array
    {
        foreach ($finals as &$match) {
            if ((int) $match['id'] !== $matchId) {
                continue;
            }

            $candidates = array_filter([$match['athlete1'], $match['athlete2']]);
            $winner     = collect($candidates)->firstWhere('id', $athleteId);
            if (! $winner) {
                break;
            }

            $match['winner']    = $winner;
            $match['winner_id'] = $athleteId;

            // Propagate winner into next round
            $finals = $this->propagateWinner($finals, $match);

            // SF losers → 3rd place match
            if (($match['round'] ?? 0) === 2) {
                $loser     = ($winner === $match['athlete1']) ? $match['athlete2'] : $match['athlete1'];
                $isFirstSF = $match['position'] === 1;
                foreach ($finals as &$pf3) {
                    if (($pf3['round'] ?? -1) === 0) {
                        $pf3[$isFirstSF ? 'athlete1' : 'athlete2'] = $loser;
                        break;
                    }
                }
                unset($pf3);
            }
            break;
        }
        unset($match);

        return $finals;
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

    /**
     * Distribute athletes and byes evenly across bracket slots (recursive halving).
     *
     * Placing all nulls at the end concentrates byes in one half and causes a cascade:
     * a lone athlete gets a bye in 32ES, another in 16ES, another in 8ES…
     * By splitting ceil(n/2) left / floor(n/2) right we guarantee that every 2-slot
     * leaf receives at least 1 real athlete (provable by induction because
     * N > bracketSize/2 always holds when bracketSize = nextPowerOfTwo(N)).
     * Result: every intermediate-round bye match is fed by TWO athletes — no cascade.
     */
    private function buildBalancedSeeds(array $athletes, int $size): array
    {
        $n     = count($athletes);
        $half  = $size / 2;           // round-2 capacity = bracketSize / 2
        $extra = $n - $half;          // athletes beyond that capacity

        // Find the largest even r (real pairs) such that p = r - extra is also even.
        // This ensures every round-2 group of two round-1 pairs is homogeneous:
        // (R,R), (R,B), (B,B), or (P,P) — never a mixed (real/phantom) group
        // that would trigger an unwanted cascade bye in round 2 or later.
        $r = intdiv($n, 2);
        if ($r % 2 !== 0) {
            $r--;
        }
        while ($r >= $extra && ($r - $extra) % 2 !== 0) {
            $r -= 2;
        }
        if ($r < $extra) {
            $r = $extra;  // fallback: minimum real pairs so phantom count = 0
        }

        $b = $n - 2 * $r;  // bye athletes (0 for N divisible by 4, else 2 or 3)

        // Layout: [real pairs block] [bye_i, null, ...] [null, null, ... phantoms]
        $seeded = array_slice($athletes, 0, 2 * $r);
        for ($i = 0; $i < $b; $i++) {
            $seeded[] = $athletes[2 * $r + $i];
            $seeded[] = null;
        }
        while (count($seeded) < $size) {
            $seeded[] = null;
        }

        return $seeded;
    }

    private function toArray(mixed $athlete): array
    {
        if (is_array($athlete)) {
            return $athlete;
        }
        return [
            'id'       => $athlete->id ?? null,
            'name'     => ($athlete->full_name ?? null) ?: (($athlete->first_name ?? '') . ' ' . ($athlete->last_name ?? '')),
            'club'     => $athlete->club ?? '',
            'category' => $athlete->category_label ?? '',
            'seed'     => null,
        ];
    }

    /**
     * Répare un bracket corrompu en réinitialisant tous les rounds intermédiaires
     * puis en repropageant les résultats du premier round depuis zéro.
     * À appeler sur un tirage dont les "exempts" ont été générés à tort.
     */
    public function repairBracket(Draw $draw): Draw
    {
        if ($draw->use_pools || ! $draw->matches) {
            return $draw;
        }

        $matches = $draw->matches;

        // Le premier round joué = numéro de round le plus élevé
        $maxRound = collect($matches)->max('round');

        // Étape 1 : réinitialiser tous les rounds intermédiaires
        foreach ($matches as &$m) {
            if ($m['round'] < $maxRound) {
                $m['athlete1']  = null;
                $m['athlete2']  = null;
                $m['winner']    = null;
                $m['winner_id'] = null;
                $m['is_bye']    = false;
            }
        }
        unset($m);

        // Étape 2 : repropager depuis chaque match du premier round qui a un résultat
        foreach ($matches as $firstRoundMatch) {
            if ($firstRoundMatch['round'] === $maxRound && $firstRoundMatch['winner'] !== null) {
                $matches = $this->propagateWinner($matches, $firstRoundMatch);
            }
        }

        $draw->matches = $matches;
        $draw->save();
        return $draw;
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

                // Auto-advance UNIQUEMENT si le slot vide en face n'a aucun match
                // alimenteur (bye structurel). Ne jamais auto-avancer si un autre
                // match doit encore fournir cet athlète (slot en attente de résultat).
                $otherIsNull = $isFirst
                    ? ($match['athlete2'] === null)
                    : ($match['athlete1'] === null);

                if ($otherIsNull) {
                    // Le match alimenteur du slot adverse est dans le round suivant.
                    // athlete1 ← position 2*targetPosition - 1
                    // athlete2 ← position 2*targetPosition
                    $feederRound    = $targetRound + 1; // == $updatedMatch['round']
                    $feederPosition = $isFirst
                        ? ($targetPosition * 2)      // alimenteur pour athlete2
                        : ($targetPosition * 2 - 1); // alimenteur pour athlete1

                    $feederExists = collect($matches)->contains(
                        fn ($m) => $m['round'] === $feederRound && $m['position'] === $feederPosition
                    );

                    if (! $feederExists) {
                        // Slot structurellement vide : bye légitime → progresser
                        $sole = $match['athlete1'] ?? $match['athlete2'];
                        $match['winner']    = $sole;
                        $match['winner_id'] = $sole['id'] ?? null;
                        $match['is_bye']    = true;
                        $matches = $this->propagateWinner($matches, $match);
                    }
                    // Si feederExists, ne rien faire : le slot sera rempli plus tard
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
