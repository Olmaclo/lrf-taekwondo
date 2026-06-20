<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Draw;
use App\Models\Ranking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $q = Ranking::with(['athlete:id,first_name,last_name,club,gender', 'event:id,name'])
            ->orderBy('points', 'desc')
            ->orderBy('wins', 'desc');

        if ($request->season) {
            $q->where('season', $request->season);
        }
        if ($request->event_id) {
            $q->where('event_id', $request->event_id);
        }
        if ($request->category) {
            $q->where('category', $request->category);
        }

        $rankings = $q->get()->map(fn ($r) => [
            'id'         => $r->id,
            'athlete'    => $r->athlete ? [
                'id'        => $r->athlete->id,
                'full_name' => $r->athlete->first_name . ' ' . $r->athlete->last_name,
                'club'      => $r->athlete->club,
                'gender'    => $r->athlete->gender,
            ] : null,
            'event'      => $r->event ? ['id' => $r->event->id, 'name' => $r->event->name] : null,
            'season'     => $r->season,
            'category'   => $r->category,
            'position'   => $r->position,
            'points'     => $r->points,
            'wins'       => $r->wins,
            'losses'     => $r->losses,
            'medal'      => $r->medal,
            'medal_color' => $r->medal_color,
        ]);

        return response()->json(['success' => true, 'data' => $rankings]);
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate([
            'athlete_id' => ['required', 'exists:athletes,id'],
            'event_id'   => ['required', 'exists:events,id'],
            'season'     => ['required', 'digits:4'],
            'category'   => ['required', 'string'],
            'position'   => ['nullable', 'integer', 'min:1'],
            'points'     => ['nullable', 'integer', 'min:0'],
            'wins'       => ['nullable', 'integer', 'min:0'],
            'losses'     => ['nullable', 'integer', 'min:0'],
        ]);

        if ($data['position'] && !isset($data['points'])) {
            $data['points'] = Ranking::pointsForPosition($data['position']);
        }

        $ranking = Ranking::updateOrCreate(
            ['athlete_id' => $data['athlete_id'], 'event_id' => $data['event_id'], 'category' => $data['category']],
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Classement mis à jour.',
            'data'    => $ranking,
        ]);
    }

    public function destroy(Ranking $ranking): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);
        $ranking->delete();
        return response()->json(['success' => true, 'message' => 'Entrée supprimée.']);
    }

    public function recalculate(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate(['event_id' => ['required', 'exists:events,id']]);
        $season = now()->year;

        $draws = Draw::where('event_id', $data['event_id'])->get();

        if ($draws->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Aucun tirage trouvé pour cet événement.'], 422);
        }

        $created = 0;

        // Précharger tous les athlètes impliqués en une seule requête (évite N+1)
        $allResults     = [];
        $allAthleteIds  = [];
        foreach ($draws as $draw) {
            $results = $this->extractResultsFromDraw($draw);
            $allResults[$draw->id] = ['category' => $draw->age_category . '|' . $draw->gender . '|' . $draw->weight_category, 'results' => $results];
            $allAthleteIds = array_merge($allAthleteIds, array_column($results, 'athlete_id'));
        }
        $athleteExists = Athlete::whereIn('id', array_unique($allAthleteIds))->pluck('id')->flip();

        foreach ($draws as $draw) {
            $category = $allResults[$draw->id]['category'];
            $results  = $allResults[$draw->id]['results'];

            foreach ($results as $result) {
                if (! isset($athleteExists[$result['athlete_id']])) continue;

                Ranking::updateOrCreate(
                    [
                        'athlete_id' => $result['athlete_id'],
                        'event_id'   => $data['event_id'],
                        'category'   => $category,
                    ],
                    [
                        'season'   => $season,
                        'position' => $result['position'],
                        'points'   => Ranking::pointsForPosition($result['position']),
                        'wins'     => $result['wins'],
                        'losses'   => $result['losses'],
                    ]
                );
                $created++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Classement recalculé : {$created} entrée(s) mise(s) à jour.",
        ]);
    }

    // ── Private helpers ────────────────────────────────────────────────────────

    private function extractResultsFromDraw(Draw $draw): array
    {
        if ($draw->use_pools && $draw->pools) {
            $pools  = $draw->pools;
            $finals = $pools['finals'] ?? [];
            $wins   = [];
            $losses = [];

            // Accumulate wins/losses from all pool matches
            foreach ($pools['pools'] ?? [] as $pool) {
                foreach ($pool['matches'] ?? [] as $m) {
                    $this->accumulateWinsLosses($m, $wins, $losses);
                }
            }
            // Also count wins/losses from finals matches
            foreach ($finals as $m) {
                if (($m['round'] ?? -1) === 0) continue;
                $this->accumulateWinsLosses($m, $wins, $losses);
            }

            return $this->extractPositionsFromBracket($finals, $wins, $losses);
        }

        if (!$draw->matches) return [];

        $wins   = [];
        $losses = [];
        foreach ($draw->matches as $m) {
            $this->accumulateWinsLosses($m, $wins, $losses);
        }

        return $this->extractPositionsFromBracket($draw->matches, $wins, $losses);
    }

    private function accumulateWinsLosses(array $m, array &$wins, array &$losses): void
    {
        $a1id = $m['athlete1']['id'] ?? null;
        $a2id = $m['athlete2']['id'] ?? null;

        if ($a1id) { $wins[$a1id] ??= 0; $losses[$a1id] ??= 0; }
        if ($a2id) { $wins[$a2id] ??= 0; $losses[$a2id] ??= 0; }

        if ($w = $m['winner_id'] ?? null) {
            $wins[$w] = ($wins[$w] ?? 0) + 1;
            foreach ([$a1id, $a2id] as $pid) {
                if ($pid && $pid !== $w) {
                    $losses[$pid] = ($losses[$pid] ?? 0) + 1;
                }
            }
        }
    }

    private function extractPositionsFromBracket(array $matches, array $wins, array $losses): array
    {
        $results    = [];
        $positioned = [];
        $col        = collect($matches);

        // 1st & 2nd: from the finale (round === 1)
        $final = $col->firstWhere('round', 1);
        if ($final && ($final['winner_id'] ?? null)) {
            $w = $final['winner_id'];
            $results[]    = ['athlete_id' => $w, 'position' => 1, 'wins' => $wins[$w] ?? 0, 'losses' => $losses[$w] ?? 0];
            $positioned[] = $w;

            foreach (['athlete1', 'athlete2'] as $k) {
                $id = $final[$k]['id'] ?? null;
                if ($id && $id !== $w && !($final[$k]['placeholder'] ?? false) && !in_array($id, $positioned)) {
                    $results[]    = ['athlete_id' => $id, 'position' => 2, 'wins' => $wins[$id] ?? 0, 'losses' => $losses[$id] ?? 0];
                    $positioned[] = $id;
                }
            }
        }

        // 3rd & 4th: from the petite finale (round === 0) if it has a winner
        $pf3 = $col->firstWhere('round', 0);
        if ($pf3 && ($pf3['winner_id'] ?? null)) {
            $w3 = $pf3['winner_id'];
            if (!in_array($w3, $positioned)) {
                $results[]    = ['athlete_id' => $w3, 'position' => 3, 'wins' => $wins[$w3] ?? 0, 'losses' => $losses[$w3] ?? 0];
                $positioned[] = $w3;
            }
            foreach (['athlete1', 'athlete2'] as $k) {
                $id = $pf3[$k]['id'] ?? null;
                if ($id && $id !== $w3 && !($pf3[$k]['placeholder'] ?? false) && !in_array($id, $positioned)) {
                    $results[]    = ['athlete_id' => $id, 'position' => 4, 'wins' => $wins[$id] ?? 0, 'losses' => $losses[$id] ?? 0];
                    $positioned[] = $id;
                }
            }
        } else {
            // Direct elimination: semi losers (round === 2) get 3rd place
            foreach ($col->where('round', 2) as $semi) {
                if (!($semi['winner_id'] ?? null)) continue;
                $sw = $semi['winner_id'];
                foreach (['athlete1', 'athlete2'] as $k) {
                    $id = $semi[$k]['id'] ?? null;
                    if ($id && $id !== $sw && !($semi[$k]['placeholder'] ?? false) && !in_array($id, $positioned)) {
                        $results[]    = ['athlete_id' => $id, 'position' => 3, 'wins' => $wins[$id] ?? 0, 'losses' => $losses[$id] ?? 0];
                        $positioned[] = $id;
                    }
                }
            }
        }

        // Remaining athletes who participated but have no position yet
        foreach (array_keys($wins) as $id) {
            if ($id && !in_array($id, $positioned)) {
                $results[] = ['athlete_id' => $id, 'position' => null, 'wins' => $wins[$id], 'losses' => $losses[$id] ?? 0];
            }
        }

        return $results;
    }
}
