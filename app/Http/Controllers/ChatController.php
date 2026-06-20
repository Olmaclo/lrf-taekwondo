<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Events\ReactionSent;
use App\Models\ChatMessage;
use App\Models\LiveBan;
use App\Models\LiveSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ChatController extends Controller
{
    /**
     * Historique des derniers messages (consultable même en replay).
     */
    public function history(LiveSession $liveSession): JsonResponse
    {
        $messages = ChatMessage::where('live_session_id', $liveSession->id)
            ->visible()
            ->latest()
            ->limit(60)
            ->get(['id', 'pseudo', 'message', 'created_at'])
            ->reverse()
            ->map(fn ($m) => [
                'id'      => $m->id,
                'pseudo'  => $m->pseudo,
                'message' => $m->message,
                'time'    => $m->created_at->format('H:i'),
            ])
            ->values();

        return response()->json(['success' => true, 'data' => $messages]);
    }

    /**
     * Envoi d'un message (spectateur anonyme avec pseudo).
     */
    public function send(Request $request, LiveSession $liveSession): JsonResponse
    {
        // Le chat n'accepte des messages que pendant le direct
        if (! $liveSession->isLive()) {
            return response()->json(['success' => false, 'message' => "Le chat n'est ouvert que pendant le direct."], 422);
        }

        $data = $request->validate([
            'pseudo'  => ['required', 'string', 'min:2', 'max:40'],
            'message' => ['required', 'string', 'min:1', 'max:500'],
        ]);

        $pseudo  = trim(preg_replace('/\s+/', ' ', strip_tags($data['pseudo'])));
        $message = trim(strip_tags($data['message']));
        $ipHash  = hash('sha256', $request->ip() . config('app.key'));

        // Banni (par pseudo ou empreinte IP) ?
        if (LiveBan::isBanned($liveSession->id, $pseudo, $ipHash)) {
            return response()->json(['success' => false, 'message' => 'Tu ne peux plus participer à ce chat.'], 403);
        }

        // Anti-spam applicatif : 1 message / 2 s par visiteur
        $key = "chat:{$liveSession->id}:{$ipHash}";
        if (RateLimiter::tooManyAttempts($key, 1)) {
            return response()->json(['success' => false, 'message' => 'Doucement ! Attends une seconde.'], 429);
        }
        RateLimiter::hit($key, 2);

        $chatMessage = ChatMessage::create([
            'live_session_id' => $liveSession->id,
            'pseudo'          => $pseudo,
            'message'         => $message,
            'ip_hash'         => $ipHash,
        ]);

        // Diffuse aux autres spectateurs ; l'émetteur l'affiche déjà localement
        broadcast(new ChatMessageSent($chatMessage))->toOthers();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'      => $chatMessage->id,
                'pseudo'  => $pseudo,
                'message' => $message,
                'time'    => $chatMessage->created_at->format('H:i'),
            ],
        ], 201);
    }

    /**
     * Réaction emoji éphémère (non stockée) — diffusée pour l'animation flottante.
     */
    public function react(Request $request, LiveSession $liveSession): JsonResponse
    {
        if (! $liveSession->isLive()) {
            return response()->json(['success' => false], 422);
        }

        $emoji   = (string) $request->input('emoji');
        $allowed = ['❤️', '👏', '🔥', '😮', '😂', '🥋', '💪', '🎉'];
        if (! in_array($emoji, $allowed, true)) {
            return response()->json(['success' => false], 422);
        }

        // Anti-flood : 5 réactions / 3 s par visiteur
        $ipHash = hash('sha256', $request->ip() . config('app.key'));
        $key    = "react:{$liveSession->id}:{$ipHash}";
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['success' => false], 429);
        }
        RateLimiter::hit($key, 3);

        broadcast(new ReactionSent($liveSession->id, $emoji))->toOthers();

        return response()->json(['success' => true]);
    }
}
