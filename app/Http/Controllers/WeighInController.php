<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Event;
use App\Services\WeightCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WeighInController extends Controller
{
    public function __construct(private WeightCategoryService $categories) {}

    public function index(string $slug): View
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $event = Event::where('slug', $slug)->firstOrFail();

        $baseQuery = Athlete::where('event_id', $event->id)
            ->where('registration_status', 'validated');

        // Agrégats en une seule requête
        $aggs = (clone $baseQuery)
            ->selectRaw("COUNT(*) AS total, SUM(weigh_in_status='passed') AS passed, SUM(weigh_in_status='failed') AS failed")
            ->first();

        $stats = [
            'total'   => (int) $aggs->total,
            'passed'  => (int) $aggs->passed,
            'failed'  => (int) $aggs->failed,
            'pending' => (int) $aggs->total - (int) $aggs->passed - (int) $aggs->failed,
        ];

        $athletes = $baseQuery
            ->orderBy('club')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $allCategories = $this->categories->getAllWeightCategories();

        return view('technical.weigh-in', compact('event', 'athletes', 'allCategories', 'stats'));
    }

    public function declare(Request $request, Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate([
            'status'       => ['required', 'in:passed,failed'],
            'actual_weight'=> ['nullable', 'numeric', 'min:10', 'max:250'],
        ]);

        // Si poids réel fourni, déterminer automatiquement pass/fail
        if (isset($data['actual_weight'])) {
            $allCats = $this->categories->getAllWeightCategories();
            $range   = $allCats[$athlete->age_category][$athlete->gender][$athlete->weight_category] ?? null;
            if ($range) {
                $w = (float) $data['actual_weight'];
                $data['status'] = ($w >= $range[0] && $w < $range[1]) ? 'passed' : 'failed';
            }
        }

        $athlete->forceFill([
            'weigh_in_status' => $data['status'],
            'weigh_in_weight' => $data['actual_weight'] ?? null,
            'weigh_in_at'     => now(),
            'weigh_in_by'     => Auth::id(),
        ])->save();

        return response()->json([
            'success' => true,
            'status'  => $data['status'],
            'label'   => $data['status'] === 'passed' ? 'Réussi' : 'Hors catégorie',
        ]);
    }

    public function reset(Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $athlete->forceFill([
            'weigh_in_status' => null,
            'weigh_in_weight' => null,
            'weigh_in_at'     => null,
            'weigh_in_by'     => null,
        ])->save();

        return response()->json(['success' => true]);
    }
}
