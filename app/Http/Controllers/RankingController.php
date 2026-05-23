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

        foreach ($draws as $draw) {
            $category = $draw->age_category . '|' . $draw->gender . '|' . $draw->weight_category;
            $results  = $this->extractResultsFromDraw($draw);

            foreach ($results as $result) {
                $athlete = Athlete::find($result['athlete_id']);
                if (!$athlete) continue;

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
        $results = [];

        if ($draw->use_pools && $draw->pools) {
            foreach ($draw->pools as $pool) {
                $stats = $this->computePoolStats($pool['matches'] ?? []);
                foreach ($stats as $athleteId => $stat) {
                    $results[] = array_merge(['athlete_id' => $athleteId], $stat);
                }
            }
            return $results;
        }

        if (!$draw->matches) return [];

        $matches = collect($draw->matches);
        $wins    = [];
        $losses  = [];
        $maxRound = 0;

        foreach ($matches as $match) {
            $round = $match['round_num'] ?? 0;
            if ($round > $maxRound) $maxRound = $round;
            if ($w = $match['winner_id'] ?? null) {
                $wins[$w]  = ($wins[$w]  ?? 0) + 1;
            }
            foreach (['athlete1_id', 'athlete2_id'] as $key) {
                if ($id = $match[$key] ?? null) {
                    $wins[$id]   = $wins[$id]   ?? 0;
                    $losses[$id] = $losses[$id] ?? 0;
                }
            }
            if ($w = $match['winner_id'] ?? null) {
                foreach (['athlete1_id', 'athlete2_id'] as $key) {
                    $loser = $match[$key] ?? null;
                    if ($loser && $loser !== $w) {
                        $losses[$loser] = ($losses[$loser] ?? 0) + 1;
                    }
                }
            }
        }

        // Determine positions from final matches
        $finals       = $matches->where('round', 'final');
        $semis        = $matches->where('round', 'semi');
        $finalistsIds = [];

        foreach ($finals as $final) {
            $winner = $final['winner_id'] ?? null;
            if ($winner) {
                $results[] = ['athlete_id' => $winner, 'position' => 1, 'wins' => $wins[$winner] ?? 0, 'losses' => 0];
                $finalistsIds[] = $winner;
                foreach (['athlete1_id', 'athlete2_id'] as $key) {
                    $id = $final[$key] ?? null;
                    if ($id && $id !== $winner) {
                        $results[] = ['athlete_id' => $id, 'position' => 2, 'wins' => $wins[$id] ?? 0, 'losses' => $losses[$id] ?? 0];
                        $finalistsIds[] = $id;
                    }
                }
            }
        }

        foreach ($semis as $semi) {
            foreach (['athlete1_id', 'athlete2_id'] as $key) {
                $id = $semi[$key] ?? null;
                if ($id && !in_array($id, $finalistsIds)) {
                    $loser = ($semi['winner_id'] ?? null) && $id !== $semi['winner_id'];
                    if ($loser) {
                        $results[] = ['athlete_id' => $id, 'position' => 3, 'wins' => $wins[$id] ?? 0, 'losses' => $losses[$id] ?? 0];
                    }
                }
            }
        }

        // Remaining athletes who had matches but no position yet
        $positioned = array_column($results, 'athlete_id');
        foreach (array_keys($wins) as $id) {
            if (!in_array($id, $positioned)) {
                $results[] = ['athlete_id' => $id, 'position' => null, 'wins' => $wins[$id], 'losses' => $losses[$id] ?? 0];
            }
        }

        return $results;
    }

    private function computePoolStats(array $matches): array
    {
        $stats = [];
        foreach ($matches as $match) {
            foreach (['athlete1_id', 'athlete2_id'] as $key) {
                $id = $match[$key] ?? null;
                if ($id) $stats[$id] ??= ['wins' => 0, 'losses' => 0];
            }
            if ($w = $match['winner_id'] ?? null) {
                $stats[$w]['wins']++;
                foreach (['athlete1_id', 'athlete2_id'] as $key) {
                    $l = $match[$key] ?? null;
                    if ($l && $l !== $w) $stats[$l]['losses']++;
                }
            }
        }
        // Sort by wins desc to assign position
        arsort($stats);
        $pos = 1;
        foreach ($stats as $id => $stat) {
            $stats[$id]['position'] = $pos++;
        }
        return $stats;
    }
}
