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

        $stats = [
            'total_events'        => Event::count(),
            'total_athletes'      => Athlete::when($eventId, fn ($q) => $q->where('event_id', $eventId))->count(),
            'validated_athletes'  => Athlete::when($eventId, fn ($q) => $q->where('event_id', $eventId))->where('registration_status', 'validated')->count(),
            'pending_athletes'    => Athlete::when($eventId, fn ($q) => $q->where('event_id', $eventId))->where('registration_status', 'pending')->count(),
            'total_coaches'       => User::role('coach')->count(),
            'pending_coaches'     => User::role('coach')->where('is_validated', false)->count(),
            'total_draws'         => Draw::count(),
            'total_clubs'         => Athlete::distinct('club')->count('club'),
            // Financial
            'total_collected'     => Athlete::when($eventId, fn ($q) => $q->where('event_id', $eventId))->whereIn('payment_status', ['paid', 'validated'])->sum('payment_amount'),
            'validated_payments'  => Athlete::when($eventId, fn ($q) => $q->where('event_id', $eventId))->where('payment_status', 'validated')->count(),
            'pending_payments'    => Athlete::when($eventId, fn ($q) => $q->where('event_id', $eventId))->where('payment_status', 'temp_validated')->count(),
            'unpaid_athletes'     => Athlete::when($eventId, fn ($q) => $q->where('event_id', $eventId))->where('payment_status', 'unpaid')->count(),
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }
}
