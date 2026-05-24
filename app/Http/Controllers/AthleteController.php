<?php

namespace App\Http\Controllers;

use App\Mail\AthleteRejectedMail;
use App\Mail\AthleteValidatedMail;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\FinancialLog;
use App\Models\User;
use App\Services\WeightCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AthleteController extends Controller
{
    public function __construct(private WeightCategoryService $categories) {}

    // ── List ──────────────────────────────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        abort_unless($user->isTechnical() || $user->isCoach() || $user->isFinancial(), 403);

        $query = Athlete::with(['coach', 'event'])
            // Coaches only see their own athletes
            ->when($user->isCoach() && ! $user->isTechnical(), fn ($q) => $q->where('coach_id', $user->id))
            ->when($request->event_id,            fn ($q) => $q->where('event_id', $request->event_id))
            ->when($request->registration_status, fn ($q) => $q->where('registration_status', $request->registration_status))
            ->when($request->payment_status,       fn ($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->club,                 fn ($q) => $q->where('club', $request->club))
            ->when($request->age_category,         fn ($q) => $q->where('age_category', $request->age_category))
            ->when($request->gender,               fn ($q) => $q->where('gender', $request->gender))
            ->when($request->weight_category,      fn ($q) => $q->where('weight_category', $request->weight_category))
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('first_name', 'like', "%{$request->search}%")
                        ->orWhere('last_name',  'like', "%{$request->search}%")
                        ->orWhere('club',       'like', "%{$request->search}%")
                        ->orWhere('license_number', 'like', "%{$request->search}%");
                });
            });

        $perPage  = min((int) ($request->per_page ?? 30), 100);
        $athletes = $query->orderBy('last_name')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $athletes->items(),
            'meta'    => [
                'total'        => $athletes->total(),
                'current_page' => $athletes->currentPage(),
                'last_page'    => $athletes->lastPage(),
            ],
        ]);
    }

    // ── Show single ───────────────────────────────────────────────────────────

    public function show(Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical() || Auth::id() === $athlete->coach_id, 403);

        return response()->json([
            'success' => true,
            'data'    => $athlete->load(['coach', 'event', 'validator']),
        ]);
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name'      => ['required', 'string', 'max:100'],
            'last_name'       => ['required', 'string', 'max:100'],
            'birth_date'      => ['required', 'date', 'before:today'],
            'birth_place'     => ['nullable', 'string', 'max:100'],
            'gender'          => ['required', Rule::in(['M', 'F'])],
            'age_category'    => ['nullable', Rule::in(['Minime', 'Cadet', 'Junior', 'Senior'])],
            'weight_category' => ['nullable', 'string'],
            'weight'          => ['nullable', 'numeric', 'min:10', 'max:200'],
            'nationality'     => ['nullable', 'string', 'max:100'],
            'club'            => ['required', 'string', 'max:100'],
            'license_number'  => ['nullable', 'string', 'max:50'],
            'event_id'        => ['required', 'exists:events,id'],
            'coach_id'        => ['nullable', 'exists:users,id'],
        ]);

        $data['created_by'] = Auth::id();

        $user = Auth::user();

        // Block coaches when registrations are closed
        if (! $user->isTechnical()) {
            $event = Event::find($data['event_id']);
            abort_unless($event && $event->isRegistrationOpen(), 422, 'Les inscriptions pour cet événement sont fermées.');
            // Force coach_id to current user — non-technical users cannot assign to another coach
            $data['coach_id'] = $user->id;
        } elseif (! isset($data['coach_id'])) {
            $data['coach_id'] = null;
        }

        // Auto-compute age_category if not provided
        if (empty($data['age_category']) && ! empty($data['birth_date'])) {
            $age = now()->diffInYears($data['birth_date']);
            $data['age_category'] = $this->categories->getAgeCategoryFromAge($age);
        }

        // Auto-compute weight_category if weight provided but category missing
        if (empty($data['weight_category']) && ! empty($data['weight']) && ! empty($data['age_category'])) {
            $data['weight_category'] = $this->categories->getWeightCategoryFromWeight(
                (float) $data['weight'], $data['age_category'], $data['gender']
            );
        }

        $athlete = Athlete::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Athlète ajouté avec succès.',
            'data'    => $athlete->load(['coach', 'event']),
        ], 201);
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical() || Auth::id() === $athlete->coach_id, 403);

        // Block coaches when registrations are closed
        if (! Auth::user()->isTechnical()) {
            $event = $athlete->event;
            abort_unless($event && $event->isRegistrationOpen(), 422, 'Les inscriptions pour cet événement sont fermées.');
        }

        $data = $request->validate([
            'first_name'      => ['sometimes', 'string', 'max:100'],
            'last_name'       => ['sometimes', 'string', 'max:100'],
            'birth_date'      => ['sometimes', 'date'],
            'birth_place'     => ['nullable', 'string'],
            'gender'          => ['sometimes', Rule::in(['M', 'F'])],
            'age_category'    => ['sometimes', Rule::in(['Minime', 'Cadet', 'Junior', 'Senior'])],
            'weight_category' => ['sometimes', 'string'],
            'club'            => ['sometimes', 'string', 'max:100'],
            'license_number'  => ['nullable', 'string', 'max:50'],
        ]);

        $data['last_modified_by'] = Auth::id();
        $athlete->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Athlète mis à jour.',
            'data'    => $athlete->fresh(['coach', 'event']),
        ]);
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    public function destroy(Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        if ($athlete->registration_status === 'validated') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer un athlète validé. Rejetez-le d\'abord.',
            ], 422);
        }

        $athlete->delete();
        return response()->json(['success' => true, 'message' => 'Athlète supprimé.']);
    }

    public function bulkDestroy(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);
        $ids = $request->validate(['ids' => ['required', 'array', 'min:1'], 'ids.*' => ['integer']])['ids'];

        $deleted = Athlete::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deleted} athlète(s) supprimé(s).",
            'deleted' => $deleted,
        ]);
    }

    // ── Validate / Reject ─────────────────────────────────────────────────────

    public function validate(Request $request, Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        if (empty($athlete->weight_category)) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de valider : catégorie de poids manquante.',
            ], 422);
        }

        DB::transaction(function () use ($athlete) {
            $athlete->forceFill([
                'registration_status' => 'validated',
                'validated_by'        => Auth::id(),
                'validated_at'        => now(),
                'rejection_reason'    => null,
            ])->save();
        });

        $athlete->load(['coach', 'event']);
        if ($athlete->coach?->email) {
            try {
                Mail::to($athlete->coach->email)->queue(new AthleteValidatedMail($athlete));
            } catch (\Throwable) {}
        }

        return response()->json(['success' => true, 'message' => 'Athlète validé.', 'data' => $athlete->fresh()]);
    }

    public function bulkValidate(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);
        $ids = $request->validate(['ids' => ['required', 'array']])['ids'];

        $updated = Athlete::whereIn('id', $ids)->update([
            'registration_status' => 'validated',
            'validated_by'        => Auth::id(),
            'validated_at'        => now(),
        ]);

        return response()->json(['success' => true, 'message' => "{$updated} athlète(s) validé(s).", 'validated' => $updated]);
    }

    public function validateByClub(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate([
            'club'     => ['required', 'string', 'max:150'],
            'event_id' => ['nullable', 'exists:events,id'],
        ]);

        $query = Athlete::where('club', $data['club'])
            ->where('registration_status', '!=', 'validated')
            ->when($data['event_id'] ?? null, fn ($q) => $q->where('event_id', $data['event_id']));

        $count = $query->count();
        $query->update([
            'registration_status' => 'validated',
            'validated_by'        => Auth::id(),
            'validated_at'        => now(),
            'rejection_reason'    => null,
        ]);

        return response()->json(['success' => true, 'message' => "{$count} athlète(s) du club validé(s).", 'validated' => $count]);
    }

    public function destroyByClub(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate([
            'club'     => ['required', 'string', 'max:150'],
            'event_id' => ['nullable', 'exists:events,id'],
        ]);

        $query = Athlete::where('club', $data['club'])
            ->when($data['event_id'] ?? null, fn ($q) => $q->where('event_id', $data['event_id']));

        $count = $query->count();
        $query->delete();

        return response()->json(['success' => true, 'message' => "{$count} athlète(s) du club supprimé(s).", 'deleted' => $count]);
    }

    public function reject(Request $request, Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);
        $data = $request->validate(['reason' => ['nullable', 'string', 'max:500']]);

        DB::transaction(function () use ($athlete, $data) {
            $athlete->forceFill([
                'registration_status' => 'rejected',
                'rejection_reason'    => $data['reason'] ?? null,
                'validated_by'        => Auth::id(),
                'validated_at'        => now(),
            ])->save();
        });

        $athlete->load(['coach', 'event']);
        if ($athlete->coach?->email) {
            try {
                Mail::to($athlete->coach->email)->queue(new AthleteRejectedMail($athlete));
            } catch (\Throwable) {}
        }

        return response()->json(['success' => true, 'message' => 'Athlète rejeté.']);
    }

    // ── Categories by event ───────────────────────────────────────────────────

    public function categoriesByEvent(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $eventId = $request->validate(['event_id' => ['required', 'exists:events,id']])['event_id'];

        $event     = Event::findOrFail($eventId);
        $eventType = $event->type;

        $categories = Athlete::where('event_id', $eventId)
            ->validated()
            ->selectRaw('age_category, gender, weight_category, COUNT(*) as count')
            ->groupBy('age_category', 'gender', 'weight_category')
            ->orderBy('age_category')
            ->get()
            ->map(fn ($row) => [
                'key'             => "{$row->age_category}|{$row->gender}|{$row->weight_category}",
                'label'           => "{$row->age_category} " . ($row->gender === 'M' ? '♂' : '♀') . " {$row->weight_category}",
                'age_category'    => $row->age_category,
                'gender'          => $row->gender,
                'weight_category' => $row->weight_category,
                'count'           => $row->count,
            ]);

        return response()->json(['success' => true, 'data' => $categories]);
    }

    // ── Weight categories dropdown ────────────────────────────────────────────

    public function weightCategories(Request $request): JsonResponse
    {
        $data = $request->validate([
            'age_category' => ['required', 'string'],
            'gender'       => ['required', Rule::in(['M', 'F'])],
        ]);

        $cats = $this->categories->getWeightCategories($data['age_category'], $data['gender']);

        return response()->json(['success' => true, 'data' => $cats]);
    }
}
