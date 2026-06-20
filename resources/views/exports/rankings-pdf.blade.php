<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9.5px; color: #1e293b; padding: 24px 28px; background: #fff; }

    .doc-header { display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2.5px solid #f59e0b; padding-bottom: 14px; margin-bottom: 20px; }
    .doc-title  { font-size: 20px; font-weight: 700; color: #0f172a; letter-spacing: -0.5px; }
    .doc-sub    { font-size: 10px; color: #64748b; margin-top: 3px; }
    .doc-meta   { text-align: right; font-size: 8.5px; color: #94a3b8; line-height: 1.6; }
    .total-chip { display: inline-block; margin-top: 8px; background: #0f172a; color: #f59e0b; padding: 3px 10px; font-size: 10px; font-weight: 700; }

    .cat-block  { margin-bottom: 22px; page-break-inside: avoid; }
    .cat-header { background: #0f172a; color: #fff; padding: 7px 12px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 0; }
    .cat-title  { font-size: 11px; font-weight: 700; letter-spacing: -0.3px; }
    .cat-count  { font-size: 8px; color: #94a3b8; }

    table       { width: 100%; border-collapse: collapse; }
    th          { background: #f8fafc; padding: 6px 10px; text-align: left; font-size: 7.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; border-bottom: 1px solid #e2e8f0; }
    td          { padding: 7px 10px; font-size: 8.5px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }

    .rank-gold   { background: #fef9c3; }
    .rank-silver { background: #f8fafc; }
    .rank-bronze { background: #fff7ed; }

    .medal { display: inline-block; width: 18px; height: 18px; border-radius: 50%; text-align: center; line-height: 18px; font-size: 8px; font-weight: 800; }
    .medal-gold   { background: #fde68a; color: #92400e; }
    .medal-silver { background: #e2e8f0; color: #475569; }
    .medal-bronze { background: #fed7aa; color: #9a3412; }

    .pts { font-size: 12px; font-weight: 800; }
    .pts-gold   { color: #d97706; }
    .pts-silver { color: #475569; }
    .pts-bronze { color: #ea580c; }

    .best-pos { display: inline-block; padding: 1px 6px; border-radius: 10px; font-size: 7.5px; font-weight: 700; }
    .bp-1 { background: #fde68a; color: #92400e; }
    .bp-2 { background: #e2e8f0; color: #475569; }
    .bp-3 { background: #fed7aa; color: #9a3412; }
    .bp-x { background: #f1f5f9; color: #64748b; }

    .footer { position: fixed; bottom: 0; left: 0; right: 0; padding: 8px 28px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 7.5px; color: #94a3b8; }
</style>
</head>
<body>

<div class="doc-header">
    <div>
        <div class="doc-title">Classements — Saison {{ $season }}</div>
        <div class="doc-sub">Ligue Régionale de Fatick Taekwondo · Cumul des points</div>
        <div class="total-chip">{{ $byCategory->sum(fn($c) => $c->count()) }} athlètes classés</div>
    </div>
    <div class="doc-meta">
        Généré le {{ $generatedAt }}<br>
        Confidentiel — usage officiel
    </div>
</div>

@foreach($byCategory as $categoryKey => $standings)
@php
    $parts       = explode('|', $categoryKey);
    $ageLabel    = $parts[0] ?? $categoryKey;
    $genderRaw   = $parts[1] ?? '';
    $weightLabel = $parts[2] ?? '';
    $genderLabel = match($genderRaw) { 'M' => 'Hommes', 'F' => 'Femmes', default => '' };
@endphp
<div class="cat-block">
    <div class="cat-header">
        <span class="cat-title">{{ $ageLabel }}{{ $genderLabel ? ' · ' . $genderLabel : '' }}{{ $weightLabel ? ' · ' . $weightLabel : '' }}</span>
        <span class="cat-count">{{ $standings->count() }} athlète(s)</span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">Rang</th>
                <th>Athlète</th>
                <th>Club</th>
                <th style="text-align:center; width:55px;">Points</th>
                <th style="text-align:center; width:50px;">Victoires</th>
                <th style="text-align:center; width:60px;">Compétitions</th>
                <th style="text-align:center; width:70px;">Meilleur résultat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($standings as $rank => $entry)
            @php
                $pos       = $rank + 1;
                $rowClass  = match($pos) { 1 => 'rank-gold', 2 => 'rank-silver', 3 => 'rank-bronze', default => '' };
                $medalClass= match($pos) { 1 => 'medal-gold', 2 => 'medal-silver', 3 => 'medal-bronze', default => '' };
                $ptsClass  = match($pos) { 1 => 'pts-gold', 2 => 'pts-silver', 3 => 'pts-bronze', default => '' };
                $bp        = $entry['best_position'];
                $bpClass   = match($bp) { 1 => 'bp-1', 2 => 'bp-2', 3 => 'bp-3', default => 'bp-x' };
                $bpLabel   = $bp ? $bp . 'ème' : '—';
            @endphp
            <tr class="{{ $rowClass }}">
                <td style="text-align:center;">
                    @if($pos <= 3)
                    <span class="medal {{ $medalClass }}">{{ $pos }}</span>
                    @else
                    <span style="color:#94a3b8; font-weight:600;">{{ $pos }}</span>
                    @endif
                </td>
                <td style="font-weight:600;">
                    {{ $entry['athlete'] ? $entry['athlete']->first_name . ' ' . $entry['athlete']->last_name : '—' }}
                </td>
                <td style="color:#64748b;">{{ $entry['athlete']?->club ?? '—' }}</td>
                <td style="text-align:center;"><span class="pts {{ $ptsClass }}">{{ $entry['total_points'] }}</span></td>
                <td style="text-align:center; font-weight:600;">{{ $entry['total_wins'] }}</td>
                <td style="text-align:center; color:#64748b;">{{ $entry['events_count'] }}</td>
                <td style="text-align:center;">
                    @if($bp)
                    <span class="best-pos {{ $bpClass }}">{{ $bpLabel }}</span>
                    @else
                    <span style="color:#cbd5e1;">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endforeach

<div class="footer">
    <span>Ligue Régionale de Fatick Taekwondo — Classements {{ $season }}</span>
    <span>Généré le {{ $generatedAt }}</span>
</div>

</body>
</html>
