<?php

namespace App\Http\Controllers;

use App\Exports\AthletesExport;
use App\Models\Athlete;
use App\Models\Event;
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

        $athletes = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="athletes-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($athletes) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            fputcsv($handle, [
                'ID', 'Prénom', 'Nom', 'Date Naissance', 'Sexe',
                'Catégorie Âge', 'Catégorie Poids', 'Club', 'Licence',
                'Événement', 'Statut Inscription', 'Statut Paiement', 'Montant Payé',
                'N° Reçu', 'Coach',
            ], ';');

            foreach ($athletes as $a) {
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
}
