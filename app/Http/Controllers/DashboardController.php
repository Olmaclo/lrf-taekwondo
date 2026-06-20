<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Draw;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // AJAX stats request — return JSON for the current user's role
        if ($request->wantsJson()) {
            return $this->jsonStats($user);
        }

        if ($user->isCoach()) {
            return redirect()->route('coach.dashboard');
        }

        if ($user->isFinancial() && ! $user->isTechnical()) {
            return redirect()->route('financial.dashboard');
        }

        return redirect()->route('technical.dashboard');
    }

    public function technical(Request $request)
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        if ($request->wantsJson()) {
            return $this->jsonStats(Auth::user());
        }

        $events      = Event::latest()->get();
        $activeEvent = $events->firstWhere('status', 'open') ?? $events->first();

        return view('dashboard.technical', compact('events', 'activeEvent'));
    }

    public function coach(Request $request)
    {
        abort_unless(Auth::user()->isCoach(), 403);

        $events = Event::open()->latest()->get();

        return view('dashboard.coach', compact('events'));
    }

    public function financial(Request $request)
    {
        abort_unless(Auth::user()->isFinancial(), 403);

        if ($request->wantsJson()) {
            return $this->jsonStats(Auth::user(), $request->integer('event_id'));
        }

        $events = Event::latest()->get();

        return view('dashboard.financial', compact('events'));
    }

    // ── Private ──────────────────────────────────────────────────────────────

    private function jsonStats($user, int $eventId = 0): JsonResponse
    {
        $eventScope = fn ($q) => $eventId ? $q->where('event_id', $eventId) : $q;

        // Agrégats athlètes en une seule requête (registration + payment)
        $athleteAggs = Athlete::when($eventId, fn ($q) => $q->where('event_id', $eventId))
            ->selectRaw("
                COUNT(*)                                                                AS total,
                SUM(registration_status = 'validated')                                 AS validated,
                SUM(registration_status = 'pending')                                   AS pending,
                COUNT(DISTINCT club)                                                    AS clubs,
                SUM(payment_status IN ('paid','validated'))                             AS paid_count,
                SUM(CASE WHEN payment_status IN ('paid','validated') THEN payment_amount ELSE 0 END) AS collected,
                SUM(payment_status = 'validated')                                       AS validated_payments,
                SUM(payment_status = 'temp_validated')                                  AS pending_payments,
                SUM(payment_status = 'unpaid')                                          AS unpaid
            ")
            ->first();

        // Agrégats coaches en une seule requête
        $coachAggs = User::role('coach')
            ->selectRaw("COUNT(*) AS total, SUM(is_validated = 0) AS pending")
            ->first();

        $stats = [
            'total_events'        => Event::count(),
            'total_draws'         => Draw::count(),
            'total_athletes'      => (int) $athleteAggs->total,
            'validated_athletes'  => (int) $athleteAggs->validated,
            'pending_athletes'    => (int) $athleteAggs->pending,
            'total_clubs'         => (int) $athleteAggs->clubs,
            'total_coaches'       => (int) $coachAggs->total,
            'pending_coaches'     => (int) $coachAggs->pending,
            'total_collected'     => (float) $athleteAggs->collected,
            'validated_payments'  => (int) $athleteAggs->validated_payments,
            'pending_payments'    => (int) $athleteAggs->pending_payments,
            'unpaid_athletes'     => (int) $athleteAggs->unpaid,
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }
}
