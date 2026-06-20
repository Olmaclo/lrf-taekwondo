<?php

namespace App\Http\Controllers;

use App\Exports\AthletesExport;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\Ranking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function athletes(Request $request): BinaryFileResponse
    {
        abort_unless(Auth::user()->isTechnical() || Auth::user()->isFinancial(), 403);

        $filters = $request->only(['event_id', 'club', 'age_category', 'gender', 'weight_category', 'registration_status', 'payment_status']);

        return Excel::download(new AthletesExport($filters), 'athletes-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function athletesPdf(Request $request)
    {
        abort_unless(Auth::user()->isTechnical() || Auth::user()->isFinancial(), 403);

        $athletes = Athlete::with(['event', 'coach'])
            ->when($request->event_id,            fn ($q) => $q->where('event_id', $request->event_id))
            ->when($request->club,                fn ($q) => $q->where('club', $request->club))
            ->when($request->age_category,        fn ($q) => $q->where('age_category', $request->age_category))
            ->when($request->gender,              fn ($q) => $q->where('gender', $request->gender))
            ->when($request->weight_category,     fn ($q) => $q->where('weight_category', $request->weight_category))
            ->when($request->registration_status, fn ($q) => $q->where('registration_status', $request->registration_status))
            ->orderBy('age_category')->orderBy('gender')->orderBy('weight_category')->orderBy('last_name')
            ->get();

        $grouped = $athletes->groupBy(function ($a) {
            $age    = $a->age_category    ?? 'Indéfini';
            $gender = \App\Models\Athlete::genderLabel($a->gender, $a->age_category ?? '');
            $weight = $a->weight_category ?? '—';
            return "{$age} · {$gender} · {$weight}";
        });

        $event = $request->event_id ? Event::find($request->event_id) : null;

        $pdf = Pdf::loadView('exports.athletes-pdf', [
            'grouped'     => $grouped,
            'athletes'    => $athletes,
            'event'       => $event,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('athletes-' . now()->format('Y-m-d') . '.pdf');
    }

    public function athletesCsv(Request $request)
    {
        abort_unless(Auth::user()->isTechnical() || Auth::user()->isFinancial(), 403);

        $query = Athlete::with(['event', 'coach'])
            ->when($request->event_id,            fn ($q) => $q->where('event_id', $request->event_id))
            ->when($request->club,                fn ($q) => $q->where('club', $request->club))
            ->when($request->age_category,        fn ($q) => $q->where('age_category', $request->age_category))
            ->when($request->gender,              fn ($q) => $q->where('gender', $request->gender))
            ->when($request->weight_category,     fn ($q) => $q->where('weight_category', $request->weight_category))
            ->when($request->registration_status, fn ($q) => $q->where('registration_status', $request->registration_status))
            ->orderBy('age_category')->orderBy('gender')->orderBy('weight_category')->orderBy('last_name');

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="athletes-' . now()->format('Y-m-d') . '.csv"',
        ];

        // Streaming en lazy() : charge par lots, mémoire constante quel que soit le volume
        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            fputcsv($handle, [
                'ID', 'Prénom', 'Nom', 'Date Naissance', 'Sexe',
                'Catégorie Âge', 'Catégorie Poids', 'Club', 'Licence',
                'Événement', 'Statut Inscription', 'Statut Paiement', 'Montant Payé',
                'N° Reçu', 'Coach',
            ], ';');

            foreach ($query->lazy() as $a) {
                fputcsv($handle, [
                    $a->id,
                    $a->first_name,
                    $a->last_name,
                    $a->birth_date?->format('d/m/Y'),
                    $a->gender,
                    $a->age_category,
                    $a->weight_category,
                    $a->club,
                    $a->license_number ?? '',
                    $a->event?->name ?? '',
                    $a->registration_status_label,
                    $a->payment_status_label,
                    $a->payment_amount,
                    $a->receipt_number ?? '',
                    $a->coach?->name ?? '',
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Rankings exports (public — no auth required) ───────────────────────────

    private function buildRankingsByCategory(int $season): \Illuminate\Support\Collection
    {
        return Ranking::where('season', $season)
            ->with(['athlete:id,first_name,last_name,club,gender'])
            ->get()
            ->groupBy('category')
            ->map(fn ($rows) => $rows->groupBy('athlete_id')
                ->map(fn ($r) => [
                    'athlete'       => $r->first()->athlete,
                    'total_points'  => $r->sum('points'),
                    'total_wins'    => $r->sum('wins'),
                    'events_count'  => $r->count(),
                    'best_position' => $r->whereNotNull('position')->min('position'),
                ])
                ->sortByDesc('total_points')
                ->values()
            )
            ->sortKeys();
    }

    public function rankingsCsv(Request $request)
    {
        $season = (int) ($request->season ?? now()->year);
        $byCategory = $this->buildRankingsByCategory($season);

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="classements-' . $season . '.csv"',
        ];

        $callback = function () use ($byCategory) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['Catégorie', 'Âge', 'Genre', 'Poids', 'Rang', 'Athlète', 'Club', 'Points', 'Victoires', 'Compétitions', 'Meilleur résultat'], ';');

            foreach ($byCategory as $categoryKey => $standings) {
                $parts  = explode('|', $categoryKey);
                $age    = $parts[0] ?? $categoryKey;
                $gender = match($parts[1] ?? '') { 'M' => 'Hommes', 'F' => 'Femmes', default => '' };
                $weight = $parts[2] ?? '';

                foreach ($standings as $rank => $entry) {
                    $a = $entry['athlete'];
                    fputcsv($out, [
                        $categoryKey,
                        $age,
                        $gender,
                        $weight,
                        $rank + 1,
                        $a ? $a->first_name . ' ' . $a->last_name : '—',
                        $a?->club ?? '—',
                        $entry['total_points'],
                        $entry['total_wins'],
                        $entry['events_count'],
                        $entry['best_position'] ? $entry['best_position'] . 'ème' : '—',
                    ], ';');
                }
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function rankingsPdf(Request $request)
    {
        $season     = (int) ($request->season ?? now()->year);
        $byCategory = $this->buildRankingsByCategory($season);

        $pdf = Pdf::loadView('exports.rankings-pdf', [
            'byCategory'  => $byCategory,
            'season'      => $season,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('classements-' . $season . '.pdf');
    }
}
