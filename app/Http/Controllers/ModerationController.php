<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageDeleted;
use App\Models\ChatMessage;
use App\Models\LiveBan;
use App\Models\LiveSession;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ModerationController extends Controller
{
    private function ensureModerator(): void
    {
        abort_unless(Auth::check() && Auth::user()->canModerateLive(), 403);
    }

    // ── Supprimer un message ────────────────────────────────────────────────────

    public function deleteMessage(LiveSession $liveSession, ChatMessage $message): JsonResponse
    {
        $this->ensureModerator();
        abort_unless($message->live_session_id === $liveSession->id, 404);

        $message->update(['is_deleted' => true]);

        broadcast(new ChatMessageDeleted($liveSession->id, [$message->id]));

        return response()->json(['success' => true, 'message' => 'Message supprimé.']);
    }

    // ── Bannir l'auteur d'un message (pseudo + IP) + purge ses messages ─────────

    public function banAuthor(LiveSession $liveSession, ChatMessage $message): JsonResponse
    {
        $this->ensureModerator();
        abort_unless($message->live_session_id === $liveSession->id, 404);

        LiveBan::firstOrCreate(
            [
                'live_session_id' => $liveSession->id,
                'pseudo'          => $message->pseudo,
                'ip_hash'         => $message->ip_hash,
            ],
            ['banned_by' => Auth::id()]
        );

        // Purge tous les messages encore visibles de cet auteur dans ce live
        $ids = ChatMessage::where('live_session_id', $liveSession->id)
            ->where('is_deleted', false)
            ->where(function ($q) use ($message) {
                $q->where('pseudo', $message->pseudo);
                if ($message->ip_hash) {
                    $q->orWhere('ip_hash', $message->ip_hash);
                }
            })
            ->pluck('id')->all();

        if ($ids) {
            ChatMessage::whereIn('id', $ids)->update(['is_deleted' => true]);
            broadcast(new ChatMessageDeleted($liveSession->id, $ids));
        }

        return response()->json(['success' => true, 'message' => "« {$message->pseudo} » a été banni."]);
    }

    // ── Gestion des modérateurs (admin + modérateurs) ──────────────────────────

    public function moderators(): JsonResponse
    {
        $this->ensureModerator();

        $users = User::orderBy('name')->get(['id', 'name', 'email'])
            ->map(fn ($u) => [
                'id'          => $u->id,
                'name'        => $u->name,
                'email'       => $u->email,
                'is_admin'    => $u->isAdmin(),
                'is_moderator'=> $u->isAdmin() || $u->hasPermissionTo('moderate-live'),
            ]);

        return response()->json(['success' => true, 'data' => $users]);
    }

    public function toggleModerator(User $user): JsonResponse
    {
        $this->ensureModerator();

        // Les admins sont modérateurs d'office — on ne touche pas à leur statut
        if ($user->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Un administrateur est déjà modérateur.'], 422);
        }

        if ($user->hasPermissionTo('moderate-live')) {
            $user->revokePermissionTo('moderate-live');
            $message = "{$user->name} n'est plus modérateur.";
            $now = false;
        } else {
            $user->givePermissionTo('moderate-live');
            $message = "{$user->name} est maintenant modérateur.";
            $now = true;
        }

        return response()->json(['success' => true, 'message' => $message, 'is_moderator' => $now]);
    }
}
