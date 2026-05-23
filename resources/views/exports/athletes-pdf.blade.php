<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 9.5px;
        color: #1e293b;
        padding: 24px 28px;
        background: #fff;
    }

    /* ── Header ── */
    .doc-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        border-bottom: 2.5px solid #f59e0b;
        padding-bottom: 14px;
        margin-bottom: 20px;
    }
    .doc-title { font-size: 20px; font-weight: 700; color: #0f172a; letter-spacing: -0.5px; }
    .doc-sub   { font-size: 10px; color: #64748b; margin-top: 3px; }
    .doc-meta  { text-align: right; font-size: 8.5px; color: #94a3b8; line-height: 1.6; }

    .total-chip {
        display: inline-block;
        margin-top: 8px;
        background: #0f172a;
        color: #f59e0b;
        padding: 3px 10px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.05em;
    }

    /* ── Category header ── */
    .cat-header {
        background: #0f172a;
        color: #f59e0b;
        padding: 7px 10px;
        font-size: 10.5px;
        font-weight: 700;
        margin-top: 18px;
        margin-bottom: 0;
        border-left: 4px solid #f59e0b;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .cat-count {
        font-size: 8.5px;
        font-weight: 400;
        color: rgba(245,158,11,0.7);
        letter-spacing: 0.06em;
    }

    /* ── Table ── */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 4px;
    }
    thead th {
        background: #f8fafc;
        color: #475569;
        padding: 5px 7px;
        text-align: left;
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        border-bottom: 1.5px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
    }
    thead th:last-child { border-right: none; }
    tbody tr { border-bottom: 1px solid #f1f5f9; }
    tbody tr:nth-child(even) { background: #fafafa; }
    tbody td {
        padding: 5px 7px;
        font-size: 9px;
        border-right: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    tbody td:last-child { border-right: none; }

    .athlete-name { font-weight: 700; color: #0f172a; }
    .athlete-first { font-weight: 400; color: #334155; }

    /* ── Status badges ── */
    .badge {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 3px;
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 0.04em;
    }
    .badge-validated { background: #dcfce7; color: #166534; }
    .badge-pending   { background: #fef3c7; color: #92400e; }
    .badge-rejected  { background: #fee2e2; color: #991b1b; }

    /* ── Footer ── */
    .doc-footer {
        margin-top: 24px;
        padding-top: 10px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        font-size: 8px;
        color: #94a3b8;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
        font-size: 12px;
    }

    /* page break between big groups */
    .page-break { page-break-after: always; }
</style>
</head>
<body>

{{-- ── Document header ── --}}
<div class="doc-header">
    <div>
        <div class="doc-title">Liste des Athlètes</div>
        <div class="doc-sub">
            Ligue Régionale de Taekwondo de Fatick — L.R.F
            @if($event)
                &nbsp;·&nbsp; {{ $event->name }}
            @endif
        </div>
        <div class="total-chip">{{ $athletes->count() }} athlète(s)</div>
    </div>
    <div class="doc-meta">
        Généré le {{ $generatedAt }}<br>
        Document officiel — usage interne
    </div>
</div>

@if($athletes->isEmpty())
    <div class="no-data">Aucun athlète correspondant aux critères sélectionnés.</div>
@else
    @foreach($grouped as $categoryLabel => $categoryAthletes)

    {{-- ── Category block ── --}}
    <div class="cat-header">
        <span>{{ $categoryLabel }}</span>
        <span class="cat-count">{{ $categoryAthletes->count() }} athlète(s)</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:22px">#</th>
                <th style="width:28%">Nom &amp; Prénom</th>
                <th style="width:22%">Club</th>
                <th style="width:14%">N° Licence</th>
                <th style="width:10%">Poids réel</th>
                <th style="width:16%">Coach</th>
                <th style="width:12%">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryAthletes as $i => $athlete)
            <tr>
                <td style="color:#94a3b8; font-weight:600;">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <span class="athlete-name">{{ strtoupper($athlete->last_name) }}</span>
                    <span class="athlete-first">&nbsp;{{ $athlete->first_name }}</span>
                </td>
                <td>{{ $athlete->club ?? '—' }}</td>
                <td style="color:#64748b;">{{ $athlete->license_number ?? '—' }}</td>
                <td style="text-align:center;">{{ $athlete->weight ? $athlete->weight . ' kg' : '—' }}</td>
                <td style="color:#64748b;">{{ $athlete->coach?->name ?? '—' }}</td>
                <td>
                    @php
                        $badgeClass = match($athlete->registration_status) {
                            'validated' => 'badge-validated',
                            'pending'   => 'badge-pending',
                            'rejected'  => 'badge-rejected',
                            default     => '',
                        };
                        $badgeLabel = match($athlete->registration_status) {
                            'validated' => 'Validé',
                            'pending'   => 'En attente',
                            'rejected'  => 'Rejeté',
                            default     => $athlete->registration_status,
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @endforeach
@endif

{{-- ── Footer ── --}}
<div class="doc-footer">
    <span>Ligue Régionale de Taekwondo de Fatick &nbsp;·&nbsp; contact@lrftaekwondo.com</span>
    <span>Généré automatiquement le {{ $generatedAt }}</span>
</div>

</body>
</html>
