<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\FinancialLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FinancialController extends Controller
{
    // ── Mark payment ──────────────────────────────────────────────────────────

    public function markPayment(Request $request, Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isFinancial(), 403);
        $this->ensureEventWritable($athlete->event);

        $data = $request->validate([
            'amount'          => ['required', 'numeric', 'min:0'],
            'payment_method'  => ['nullable', 'string', 'max:50'],
            'transaction_ref' => ['nullable', 'string', 'max:100'],
            'notes'           => ['nullable', 'string'],
        ]);

        $prevStatus    = $athlete->payment_status;
        $receiptNumber = $athlete->receipt_number ?? Athlete::generateReceiptNumber();

        DB::transaction(function () use ($athlete, $data, $prevStatus, $receiptNumber) {
            $athlete->forceFill([
                'payment_status'  => 'temp_validated',
                'payment_amount'  => $data['amount'],
                'payment_method'  => $data['payment_method'] ?? null,
                'transaction_ref' => $data['transaction_ref'] ?? null,
                'receipt_number'  => $receiptNumber,
                'payment_date'    => now(),
            ])->save();

            FinancialLog::create([
                'athlete_id'      => $athlete->id,
                'event_id'        => $athlete->event_id,
                'user_id'         => Auth::id(),
                'action'          => 'payment_recorded',
                'previous_status' => $prevStatus,
                'new_status'      => 'temp_validated',
                'amount'          => $data['amount'],
                'notes'           => $data['notes'] ?? null,
            ]);
        });

        return response()->json([
            'success'        => true,
            'message'        => 'Paiement enregistré.',
            'receipt_number' => $receiptNumber,
            'data'           => $athlete->fresh(),
        ]);
    }

    public function editPayment(Request $request, Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isFinancial(), 403);
        $this->ensureEventWritable($athlete->event);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['unpaid', 'temp_validated', 'paid', 'validated'])],
            'notes'  => ['nullable', 'string'],
        ]);

        $prev = $athlete->payment_status;

        DB::transaction(function () use ($athlete, $data, $prev) {
            $athlete->forceFill([
                'payment_status' => $data['status'],
                'payment_amount' => $data['amount'],
            ])->save();

            FinancialLog::create([
                'athlete_id'      => $athlete->id,
                'event_id'        => $athlete->event_id,
                'user_id'         => Auth::id(),
                'action'          => 'payment_edited',
                'previous_status' => $prev,
                'new_status'      => $data['status'],
                'amount'          => $data['amount'],
                'notes'           => $data['notes'] ?? null,
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Paiement modifié.', 'data' => $athlete->fresh()]);
    }

    // ── Temporary validation ──────────────────────────────────────────────────

    public function tempValidate(Request $request, Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isFinancial(), 403);
        $this->ensureEventWritable($athlete->event);

        $data = $request->validate([
            'deadline' => ['required', 'date', 'after:today'],
            'notes'    => ['nullable', 'string'],
        ]);

        $prev = $athlete->payment_status;

        DB::transaction(function () use ($athlete, $data, $prev) {
            $athlete->forceFill([
                'payment_status'           => 'temp_validated',
                'temp_validation_deadline' => $data['deadline'],
                'temp_validation_notes'    => $data['notes'] ?? null,
                'temp_validated_by'        => Auth::id(),
                'temp_validated_at'        => now(),
            ])->save();

            FinancialLog::create([
                'athlete_id'      => $athlete->id,
                'event_id'        => $athlete->event_id,
                'user_id'         => Auth::id(),
                'action'          => 'temp_validated',
                'previous_status' => $prev,
                'new_status'      => 'temp_validated',
                'notes'           => $data['notes'] ?? null,
                'metadata'        => ['deadline' => $data['deadline']],
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Validation temporaire accordée.', 'data' => $athlete->fresh()]);
    }

    public function bulkTempValidate(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isFinancial(), 403);

        $data = $request->validate([
            'ids'      => ['required', 'array'],
            'deadline' => ['nullable', 'date', 'after:today'],
            'notes'    => ['nullable', 'string'],
        ]);

        $query = Athlete::whereIn('id', $data['ids'])->where('registration_status', 'validated');
        // Ne pas toucher aux paiements d'événements clôturés (sauf technicien)
        if (! Auth::user()->isTechnical()) {
            $query->whereHas('event', fn ($q) => $q->active());
        }

        $updated = $query->update([
            'payment_status'           => 'temp_validated',
            'temp_validation_deadline' => $data['deadline'] ?? null,
            'temp_validation_notes'    => $data['notes'] ?? null,
            'temp_validated_by'        => Auth::id(),
            'temp_validated_at'        => now(),
        ]);

        return response()->json(['success' => true, 'message' => "{$updated} athlète(s) pré-validé(s).", 'updated' => $updated]);
    }

    // ── Definitive validation ─────────────────────────────────────────────────

    public function definitiveValidate(Request $request, Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isFinancial(), 403);
        $this->ensureEventWritable($athlete->event);

        $prev = $athlete->payment_status;

        DB::transaction(function () use ($athlete, $prev) {
            $athlete->forceFill(['payment_status' => 'validated'])->save();

            FinancialLog::create([
                'athlete_id'      => $athlete->id,
                'event_id'        => $athlete->event_id,
                'user_id'         => Auth::id(),
                'action'          => 'definitive_validated',
                'previous_status' => $prev,
                'new_status'      => 'validated',
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Paiement validé définitivement.', 'data' => $athlete->fresh()]);
    }

    public function bulkDefinitiveValidate(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isFinancial(), 403);

        $ids   = $request->validate(['ids' => ['required', 'array', 'max:500']])['ids'];
        $query = Athlete::whereIn('id', $ids)->where('registration_status', 'validated');
        // Ne pas toucher aux paiements d'événements clôturés (sauf technicien)
        if (! Auth::user()->isTechnical()) {
            $query->whereHas('event', fn ($q) => $q->active());
        }

        $updated = $query->update(['payment_status' => 'validated']);

        return response()->json(['success' => true, 'message' => "{$updated} paiement(s) validé(s).", 'updated' => $updated]);
    }

    // ── Receipt PDF ───────────────────────────────────────────────────────────

    public function generateReceipt(Athlete $athlete): Response
    {
        abort_unless(Auth::user()->isFinancial(), 403);
        abort_if($athlete->payment_status === 'unpaid', 422, 'Aucun paiement enregistré.');

        DB::transaction(function () use ($athlete) {
            if (! $athlete->receipt_number) {
                $athlete->forceFill(['receipt_number' => Athlete::generateReceiptNumber()])->save();
            }

            FinancialLog::create([
                'athlete_id' => $athlete->id,
                'event_id'   => $athlete->event_id,
                'user_id'    => Auth::id(),
                'action'     => 'receipt_generated',
            ]);
        });

        $pdf = Pdf::loadView('pdf.receipt', ['athlete' => $athlete->fresh()->load('event', 'coach')])
            ->setPaper('a4');

        return $pdf->download("recu-{$athlete->receipt_number}.pdf");
    }

    // ── Payment details ───────────────────────────────────────────────────────

    public function paymentDetails(Athlete $athlete): JsonResponse
    {
        abort_unless(Auth::user()->isFinancial(), 403);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                       => $athlete->id,
                'full_name'                => $athlete->full_name,
                'club'                     => $athlete->club,
                'category_label'           => $athlete->category_label,
                'payment_status'           => $athlete->payment_status,
                'payment_status_label'     => $athlete->payment_status_label,
                'payment_amount'           => $athlete->payment_amount,
                'receipt_number'           => $athlete->receipt_number,
                'payment_date'             => $athlete->payment_date?->format('d/m/Y H:i'),
                'temp_validation_deadline' => $athlete->temp_validation_deadline?->format('d/m/Y'),
                'temp_validation_notes'    => $athlete->temp_validation_notes,
                'event_name'               => $athlete->event?->name,
            ],
        ]);
    }
}
