<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\LiveSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LiveSessionController extends Controller
{
    // ── Page de gestion (admin) ────────────────────────────────────────────────

    public function manage(): View
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $events = Event::orderByDesc('start_date')->get(['id', 'name']);

        return view('dashboard.live', compact('events'));
    }

    // ── API : liste ────────────────────────────────────────────────────────────

    public function index(): JsonResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $lives = LiveSession::with('event:id,name,slug')
            ->latest()
            ->get()
            ->map(fn ($l) => [
                'id'               => $l->id,
                'title'            => $l->title,
                'youtube_video_id' => $l->youtube_video_id,
                'status'           => $l->status,
                'status_label'     => $l->status_label,
                'event'            => $l->event ? ['id' => $l->event->id, 'name' => $l->event->name, 'slug' => $l->event->slug] : null,
                'watch_url'        => $l->watch_url,
                'public_url'       => route('public.live', $l),
                'peak_viewers'     => $l->peak_viewers,
                'started_at'       => $l->started_at?->format('d/m/Y H:i'),
                'ended_at'         => $l->ended_at?->format('d/m/Y H:i'),
            ]);

        return response()->json(['success' => true, 'data' => $lives]);
    }

    // ── API : créer ────────────────────────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'event_id'    => ['required', 'exists:events,id'],
            'title'       => ['required', 'string', 'max:200'],
            'youtube'     => ['required', 'string', 'max:300'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $videoId = LiveSession::extractYoutubeId($data['youtube']);
        if (! $videoId) {
            return response()->json([
                'success' => false,
                'message' => 'Lien ou identifiant YouTube invalide. Colle l\'URL du live ou son ID.',
            ], 422);
        }

        $live = LiveSession::create([
            'event_id'         => $data['event_id'],
            'title'            => $data['title'],
            'youtube_video_id' => $videoId,
            'description'      => $data['description'] ?? null,
            'status'           => 'scheduled',
            'created_by'       => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Direct créé.',
            'data'    => $live->load('event:id,name,slug'),
        ], 201);
    }

    // ── API : modifier ─────────────────────────────────────────────────────────

    public function update(Request $request, LiveSession $liveSession): JsonResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'title'       => ['sometimes', 'string', 'max:200'],
            'youtube'     => ['sometimes', 'string', 'max:300'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        if (! empty($data['youtube'])) {
            $videoId = LiveSession::extractYoutubeId($data['youtube']);
            if (! $videoId) {
                return response()->json(['success' => false, 'message' => 'Lien YouTube invalide.'], 422);
            }
            $data['youtube_video_id'] = $videoId;
        }
        unset($data['youtube']);

        $liveSession->update($data);

        return response()->json(['success' => true, 'message' => 'Direct mis à jour.', 'data' => $liveSession->fresh()]);
    }

    // ── API : démarrer / arrêter ───────────────────────────────────────────────

    public function start(LiveSession $liveSession): JsonResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $liveSession->update([
            'status'     => 'live',
            'started_at' => $liveSession->started_at ?? now(),
            'ended_at'   => null,
        ]);

        return response()->json(['success' => true, 'message' => 'Le direct est lancé 🔴', 'data' => $liveSession->fresh()]);
    }

    public function stop(LiveSession $liveSession): JsonResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $liveSession->update(['status' => 'ended', 'ended_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Le direct est terminé. Le replay reste disponible.', 'data' => $liveSession->fresh()]);
    }

    // ── API : supprimer ────────────────────────────────────────────────────────

    public function destroy(LiveSession $liveSession): JsonResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $liveSession->delete();

        return response()->json(['success' => true, 'message' => 'Direct supprimé.']);
    }
}
