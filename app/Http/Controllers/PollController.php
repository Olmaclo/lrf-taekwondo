<?php

namespace App\Http\Controllers;

use App\Events\PollStarted;
use App\Events\PollUpdated;
use App\Models\LiveSession;
use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    private function ensureModerator(): void
    {
        abort_unless(Auth::check() && Auth::user()->canModerateLive(), 403);
    }

    private function voterHash(Request $request): string
    {
        return hash('sha256', $request->ip() . config('app.key'));
    }

    // ── Sondage actif (chargement initial, public) ──────────────────────────────

    public function current(Request $request, LiveSession $liveSession): JsonResponse
    {
        $poll = Poll::where('live_session_id', $liveSession->id)->active()->latest()->first();

        if (! $poll) {
            return response()->json(['success' => true, 'data' => null]);
        }

        $payload = $poll->broadcastPayload();
        $vote = $poll->votes()->where('voter_hash', $this->voterHash($request))->first();
        $payload['voted'] = $vote?->option_index;

        return response()->json(['success' => true, 'data' => $payload]);
    }

    // ── Lancer un sondage (modérateur) ──────────────────────────────────────────

    public function start(Request $request, LiveSession $liveSession): JsonResponse
    {
        $this->ensureModerator();

        $data = $request->validate([
            'question'    => ['required', 'string', 'max:200'],
            'options'     => ['required', 'array', 'min:2', 'max:4'],
            'options.*'   => ['required', 'string', 'max:80'],
        ]);

        // Clore tout sondage encore actif avant d'en lancer un nouveau
        Poll::where('live_session_id', $liveSession->id)->active()->update(['status' => 'closed']);

        $poll = Poll::create([
            'live_session_id' => $liveSession->id,
            'question'        => $data['question'],
            'options'         => array_values($data['options']),
            'status'          => 'active',
            'created_by'      => Auth::id(),
        ]);

        broadcast(new PollStarted($poll));

        return response()->json(['success' => true, 'data' => $poll->broadcastPayload()], 201);
    }

    // ── Voter (public) ──────────────────────────────────────────────────────────

    public function vote(Request $request, LiveSession $liveSession, Poll $poll): JsonResponse
    {
        abort_unless($poll->live_session_id === $liveSession->id, 404);

        if (! $poll->isActive()) {
            return response()->json(['success' => false, 'message' => 'Ce sondage est clos.'], 422);
        }

        $index = (int) $request->input('option_index');
        if ($index < 0 || $index >= count($poll->options)) {
            return response()->json(['success' => false, 'message' => 'Choix invalide.'], 422);
        }

        $hash = $this->voterHash($request);

        if (PollVote::where('poll_id', $poll->id)->where('voter_hash', $hash)->exists()) {
            return response()->json(['success' => false, 'message' => 'Tu as déjà voté.'], 422);
        }

        PollVote::create(['poll_id' => $poll->id, 'option_index' => $index, 'voter_hash' => $hash]);

        $poll->refresh();
        broadcast(new PollUpdated($poll))->toOthers();

        return response()->json(['success' => true, 'data' => $poll->broadcastPayload(), 'voted' => $index]);
    }

    // ── Clore un sondage (modérateur) ───────────────────────────────────────────

    public function close(Poll $poll): JsonResponse
    {
        $this->ensureModerator();

        $poll->update(['status' => 'closed']);
        broadcast(new PollUpdated($poll->refresh()));

        return response()->json(['success' => true, 'message' => 'Sondage clos.']);
    }
}
