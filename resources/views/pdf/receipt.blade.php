<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Reçu de paiement — {{ $athlete->full_name }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #1e293b; background: #fff; }

    .page { width: 210mm; min-height: 148mm; padding: 12mm; position: relative; }

    /* Header */
    .header { display: flex; align-items: center; justify-content: space-between; padding-bottom: 8mm; border-bottom: 3px solid #f59e0b; margin-bottom: 6mm; }
    .logo-block { display: flex; align-items: center; gap: 8px; }
    .logo-circle { width: 44px; height: 44px; background: #f59e0b; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 22px; color: #0f172a; }
    .org-name { font-size: 18px; font-weight: 900; color: #0f172a; letter-spacing: -0.5px; }
    .org-sub { font-size: 10px; color: #64748b; margin-top: 2px; }
    .receipt-badge { text-align: right; }
    .receipt-title { font-size: 16px; font-weight: 700; color: #f59e0b; text-transform: uppercase; letter-spacing: 1px; }
    .receipt-number { font-size: 11px; color: #64748b; margin-top: 2px; font-family: monospace; }

    /* Two-column layout */
    .body { display: flex; gap: 6mm; margin-bottom: 5mm; }
    .col { flex: 1; }

    .section-title { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; margin-bottom: 3mm; padding-bottom: 1mm; border-bottom: 1px solid #e2e8f0; }

    .field { display: flex; margin-bottom: 2.5mm; }
    .field-label { width: 38mm; font-size: 10px; color: #64748b; flex-shrink: 0; }
    .field-value { font-size: 10px; color: #0f172a; font-weight: 500; }

    /* Amount box */
    .amount-box { background: #fef3c7; border: 2px solid #f59e0b; border-radius: 6px; padding: 4mm 6mm; margin-bottom: 5mm; display: flex; align-items: center; justify-content: space-between; }
    .amount-label { font-size: 11px; color: #92400e; font-weight: 600; }
    .amount-value { font-size: 22px; font-weight: 900; color: #78350f; }
    .amount-currency { font-size: 12px; font-weight: 600; color: #92400e; margin-left: 3px; }

    /* Status badge */
    .status-badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-validated { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
    .status-paid { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
    .status-temp { background: #fff7ed; color: #9a3412; border: 1px solid #fdba74; }

    /* Footer */
    .footer { border-top: 1px dashed #cbd5e1; padding-top: 4mm; display: flex; justify-content: space-between; align-items: flex-end; }
    .footer-note { font-size: 9px; color: #94a3b8; max-width: 100mm; line-height: 1.5; }
    .signature-block { text-align: center; }
    .signature-line { width: 45mm; border-bottom: 1px solid #94a3b8; margin-bottom: 1mm; height: 8mm; }
    .signature-label { font-size: 9px; color: #64748b; }

    /* Watermark for duplicates */
    .watermark { position: fixed; top: 40%; left: 15%; font-size: 72px; font-weight: 900; color: rgba(245,158,11,0.06); transform: rotate(-30deg); pointer-events: none; z-index: 0; text-transform: uppercase; letter-spacing: 4px; }

    @page { size: A5 landscape; margin: 0; }
</style>
</head>
<body>

<div class="watermark">L.R.F</div>

<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="logo-block">
            <div class="logo-circle">LRF</div>
            <div>
                <div class="org-name">Ligue de Fatick</div>
                <div class="org-sub">Ligue Régionale de Taekwondo de Fatick</div>
            </div>
        </div>
        <div class="receipt-badge">
            <div class="receipt-title">Reçu de paiement</div>
            <div class="receipt-number">N° {{ $athlete->receipt_number ?? 'REC-' . str_pad($athlete->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="receipt-number">Émis le {{ now()->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    {{-- Amount --}}
    <div class="amount-box">
        <div class="amount-label">Montant encaissé</div>
        <div>
            <span class="amount-value">{{ number_format($athlete->payment_amount ?? 0, 0, ',', ' ') }}</span>
            <span class="amount-currency">FCFA</span>
        </div>
    </div>

    {{-- Two columns --}}
    <div class="body">
        <div class="col">
            <div class="section-title">Informations athlète</div>
            <div class="field">
                <span class="field-label">Nom complet</span>
                <span class="field-value">{{ $athlete->full_name }}</span>
            </div>
            <div class="field">
                <span class="field-label">Date de naissance</span>
                <span class="field-value">{{ $athlete->birth_date?->format('d/m/Y') ?? '—' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Genre</span>
                <span class="field-value">{{ $athlete->gender === 'M' ? 'Masculin' : 'Féminin' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Club</span>
                <span class="field-value">{{ $athlete->club ?? '—' }}</span>
            </div>
            <div class="field">
                <span class="field-label">N° Licence</span>
                <span class="field-value">{{ $athlete->license_number ?? '—' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Coach</span>
                <span class="field-value">{{ $athlete->coach?->name ?? '—' }}</span>
            </div>
        </div>

        <div class="col">
            <div class="section-title">Inscription & Paiement</div>
            <div class="field">
                <span class="field-label">Événement</span>
                <span class="field-value">{{ $athlete->event?->name ?? '—' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Catégorie âge</span>
                <span class="field-value">{{ $athlete->age_category ?? '—' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Catégorie poids</span>
                <span class="field-value">{{ $athlete->weight_category ?? '—' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Mode de paiement</span>
                <span class="field-value">{{ match($athlete->payment_method ?? '') {
                    'cash'         => 'Espèces',
                    'transfer'     => 'Virement',
                    'mobile_money' => 'Mobile Money',
                    'check'        => 'Chèque',
                    default        => $athlete->payment_method ?? '—'
                } }}</span>
            </div>
            @if($athlete->transaction_ref)
            <div class="field">
                <span class="field-label">Réf. transaction</span>
                <span class="field-value" style="font-family:monospace">{{ $athlete->transaction_ref }}</span>
            </div>
            @endif
            <div class="field">
                <span class="field-label">Statut</span>
                <span class="status-badge {{ match($athlete->payment_status ?? '') {
                    'validated'     => 'status-validated',
                    'paid'          => 'status-paid',
                    'temp_validated'=> 'status-temp',
                    default         => 'status-temp'
                } }}">{{ $athlete->payment_status_label }}</span>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-note">
            Ce reçu atteste du paiement des frais d'inscription à l'événement susmentionné.<br>
            Document officiel émis par la Ligue de Fatick — Ligue Régionale de Taekwondo de Fatick.<br>
            Conservez ce document pour toute réclamation ultérieure.
        </div>
        <div class="signature-block">
            <div class="signature-line"></div>
            <div class="signature-label">Signature &amp; cachet officiel</div>
        </div>
    </div>

</div>
</body>
</html>
