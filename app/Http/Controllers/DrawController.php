<?php

namespace App\Http\Controllers;

use App\Models\Draw;
use App\Models\Event;
use App\Services\DrawGenerationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DrawController extends Controller
{
    public function __construct(private DrawGenerationService $drawService) {}

    // ── Generate ──────────────────────────────────────────────────────────────

    public function generate(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate([
            'event_id'        => ['required', 'exists:events,id'],
            'age_category'    => ['required', Rule::in(['Minime', 'Cadet', 'Junior', 'Senior'])],
            'gender'          => ['required', Rule::in(['M', 'F'])],
            'weight_category' => ['required', 'string'],
        ]);

        $event = Event::findOrFail($data['event_id']);

        try {
            $draw = $this->drawService->generate(
                $event,
                $data['age_category'],
                $data['gender'],
                $data['weight_category']
            );

            return response()->json([
                'success' => true,
                'message' => "Tirage généré avec succès ({$draw->total_athletes} athlète(s)).",
                'data'    => $draw->load('generator', 'event'),
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // ── List by event ─────────────────────────────────────────────────────────

    public function byEvent(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $eventId = $request->validate(['event_id' => ['nullable', 'exists:events,id']])['event_id'] ?? null;

        $draws = Draw::when($eventId, fn ($q) => $q->where('event_id', $eventId))
            ->with(['generator', 'event'])
            ->orderBy('category')
            ->get()
            ->map(fn ($d) => [
                'id'             => $d->id,
                'category'       => $d->category,
                'event_name'     => $d->event?->name,
                'event_slug'     => $d->event?->slug,
                'total_athletes' => $d->total_athletes,
                'use_pools'      => $d->use_pools,
                'generated_at'   => $d->generated_at->format('d/m/Y H:i'),
                'generated_by'   => $d->generator?->name,
            ]);

        return response()->json(['success' => true, 'data' => $draws]);
    }

    // ── Show details ──────────────────────────────────────────────────────────

    public function show(Draw $draw): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $draw->loadMissing(['event', 'generator']);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'             => $draw->id,
                'category'       => $draw->category,
                'age_category'   => $draw->age_category,
                'gender'         => $draw->gender,
                'weight_category'=> $draw->weight_category,
                'total_athletes' => $draw->total_athletes,
                'use_pools'      => $draw->use_pools,
                'matches'        => $draw->matches,
                'pools'          => $draw->pools,
                'event_title'    => $draw->event->name,
                'event_slug'     => $draw->event->slug,
                'generated_at'   => $draw->generated_at->format('d/m/Y H:i'),
                'generated_by'   => $draw->generator?->name,
                'updated_at'     => $draw->updated_at->toISOString(),
            ],
        ]);
    }

    // ── Public bracket partial (AJAX, no auth required) ───────────────────────

    public function bracketPartial(Draw $draw): ViewContract
    {
        $draw->load('event');
        $isAdmin = Auth::check() && Auth::user()?->isTechnical();
        return view('_partials.draw-bracket', [
            'draw'    => $draw,
            'event'   => $draw->event,
            'isAdmin' => $isAdmin,
        ]);
    }

    // ── Lightweight status for public polling ─────────────────────────────────

    public function status(Draw $draw): JsonResponse
    {
        return response()->json([
            'id'         => $draw->id,
            'updated_at' => $draw->updated_at->toISOString(),
        ]);
    }

    // ── Set winner ────────────────────────────────────────────────────────────

    public function setWinner(Request $request, Draw $draw): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate([
            'match_id'   => ['required', 'integer'],
            'athlete_id' => ['required', 'integer'],
        ]);

        $draw = $this->drawService->setMatchWinner($draw, $data['match_id'], $data['athlete_id']);

        return response()->json(['success' => true, 'message' => 'Vainqueur enregistré.', 'data' => $draw]);
    }

    public function resetWinner(Request $request, Draw $draw): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);
        $matchId = $request->validate(['match_id' => ['required', 'integer']])['match_id'];

        if ($draw->use_pools && $draw->pools) {
            $pools = $draw->pools;
            foreach ($pools['pools'] as &$pool) {
                foreach ($pool['matches'] as &$match) {
                    if ($match['id'] === $matchId) { $match['winner'] = null; }
                }
            }
            $draw->pools = $pools;
        } else {
            $matches = $draw->matches;
            foreach ($matches as &$match) {
                if ($match['id'] === $matchId) { $match['winner'] = null; break; }
            }
            $draw->matches = $matches;
        }

        $draw->save();
        return response()->json(['success' => true, 'message' => 'Résultat réinitialisé.']);
    }

    // ── Repair bracket ───────────────────────────────────────────────────────

    public function repair(Draw $draw): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $draw = $this->drawService->repairBracket($draw);

        return response()->json(['success' => true, 'message' => 'Bracket réparé avec succès.', 'data' => $draw]);
    }

    // ── PDF export ────────────────────────────────────────────────────────────

    public function bracketPdf(Draw $draw)
    {
        $draw->load('event');

        $roundLabel = function (int $round, int $maxRound): string {
            $remaining = $round;
            return match (true) {
                $remaining === 1                     => 'Finale',
                $remaining === 2                     => 'Demi-finales',
                $remaining === 3                     => 'Quarts de finale',
                $remaining === 4                     => 'Huitièmes de finale',
                default                              => 'Tour ' . $remaining,
            };
        };

        $byRound = [];
        if ($draw->matches) {
            $maxRound = collect($draw->matches)->max('round') ?? 1;
            foreach ($draw->matches as $m) {
                $label = $roundLabel($m['round'], $maxRound);
                $byRound[$m['round']]['label']     = $label;
                $byRound[$m['round']]['matches'][] = $m;
            }
            krsort($byRound);
        }

        $pdf = Pdf::loadView('exports.bracket-pdf', [
            'draw'        => $draw,
            'event'       => $draw->event,
            'byRound'     => $byRound,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ])->setPaper('a4', 'portrait');

        $slug = $draw->event?->slug ?? 'draw';
        return $pdf->download("tirage-{$slug}-{$draw->category}.pdf");
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    public function destroy(Draw $draw): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);
        $draw->delete();
        return response()->json(['success' => true, 'message' => 'Tirage supprimé.']);
    }
}
