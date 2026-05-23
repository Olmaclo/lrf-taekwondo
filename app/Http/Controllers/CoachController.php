<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoachController extends Controller
{
    public function index(): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $coaches = User::role('coach')
            ->withCount('athletes')
            ->orderBy('name')
            ->get()
            ->map(fn ($c) => [
                'id'              => $c->id,
                'name'            => $c->name,
                'email'           => $c->email,
                'phone'           => $c->phone,
                'club'            => $c->club,
                'is_validated'    => $c->is_validated,
                'account_status'  => $c->account_status,
                'athletes_count'  => $c->athletes_count,
                'avatar_url'      => $c->avatar_url,
                'created_at'      => $c->created_at->format('d/m/Y'),
            ]);

        return response()->json(['success' => true, 'data' => $coaches]);
    }

    public function show(User $coach): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $athletes = Athlete::where('coach_id', $coach->id)
            ->with('event')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => array_merge($coach->toArray(), [
                'athletes'       => $athletes,
                'athletes_count' => $athletes->count(),
                'avatar_url'     => $coach->avatar_url,
            ]),
        ]);
    }

    public function validate(User $coach): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $coach->update(['is_validated' => true, 'account_status' => 'approved']);

        return response()->json(['success' => true, 'message' => "Coach {$coach->name} validé."]);
    }

    public function reject(Request $request, User $coach): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $coach->update(['is_validated' => false, 'account_status' => 'rejected']);

        return response()->json(['success' => true, 'message' => "Coach {$coach->name} rejeté."]);
    }

    public function bulkValidate(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);
        $ids = $request->validate(['ids' => ['required', 'array']])['ids'];

        User::whereIn('id', $ids)->update(['is_validated' => true, 'account_status' => 'approved']);

        return response()->json(['success' => true, 'message' => count($ids) . ' coach(s) validé(s).']);
    }

    public function bulkReject(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);
        $ids = $request->validate(['ids' => ['required', 'array']])['ids'];

        User::whereIn('id', $ids)->update(['is_validated' => false, 'account_status' => 'rejected']);

        return response()->json(['success' => true, 'message' => count($ids) . ' coach(s) rejeté(s).']);
    }

    public function destroy(User $coach): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);
        abort_if($coach->id === Auth::id(), 403, 'Impossible de supprimer son propre compte.');

        $coach->delete();

        return response()->json(['success' => true, 'message' => 'Coach supprimé.']);
    }

    // ── Coach self-service: manage own athletes ────────────────────────────────

    public function unregisterAthlete(Request $request, Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::id() === $athlete->coach_id || Auth::user()->isTechnical(), 403);
        abort_if($athlete->registration_status === 'validated', 422, 'Impossible de désinscrire un athlète déjà validé.');

        // Block coaches when registrations are closed
        if (! Auth::user()->isTechnical()) {
            $event = $athlete->event;
            abort_unless($event && $event->isRegistrationOpen(), 422, 'Les inscriptions pour cet événement sont fermées. Contactez l\'équipe technique.');
        }

        $athlete->delete();

        return response()->json(['success' => true, 'message' => 'Athlète désinscrit.']);
    }
}
