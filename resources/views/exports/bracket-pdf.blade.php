<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9.5px; color: #1e293b; padding: 24px 28px; background: #fff; }

    .doc-header { display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2.5px solid #f59e0b; padding-bottom: 14px; margin-bottom: 20px; }
    .doc-title   { font-size: 18px; font-weight: 700; color: #0f172a; letter-spacing: -0.5px; }
    .doc-cat     { display: inline-block; margin-top: 6px; background: #f59e0b; color: #000; padding: 3px 10px; font-size: 10px; font-weight: 800; }
    .doc-meta    { text-align: right; font-size: 8.5px; color: #94a3b8; line-height: 1.6; }

    .winner-box  { background: #fef9c3; border: 2px solid #f59e0b; border-radius: 4px; padding: 10px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
    .winner-lbl  { font-size: 8px; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: 0.1em; }
    .winner-name { font-size: 14px; font-weight: 800; color: #92400e; }
    .winner-club { font-size: 9px; color: #a16207; margin-top: 1px; }

    .round-block { margin-bottom: 18px; page-break-inside: avoid; }
    .round-hdr   { background: #0f172a; color: #fff; padding: 6px 12px; font-size: 10px; font-weight: 700; margin-bottom: 0; letter-spacing: -0.2px; }

    table        { width: 100%; border-collapse: collapse; }
    th           { background: #f8fafc; padding: 5px 10px; text-align: left; font-size: 7.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; border-bottom: 1px solid #e2e8f0; }
    td           { padding: 7px 10px; font-size: 8.5px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }

    .match-no    { color: #94a3b8; font-size: 8px; font-weight: 700; }
    .ath-name    { font-weight: 600; }
    .ath-club    { color: #64748b; font-size: 7.5px; }
    .vs          { color: #cbd5e1; font-weight: 700; font-size: 8px; text-align: center; padding: 0 6px; }
    .winner-cell { color: #d97706; font-weight: 800; }
    .bye-row     { background: #f8fafc; }
    .bye-lbl     { color: #94a3b8; font-style: italic; }

    .pool-block  { margin-bottom: 22px; page-break-inside: avoid; }
    .pool-hdr    { background: #1e40af; color: #fff; padding: 6px 12px; font-size: 10px; font-weight: 700; margin-bottom: 0; }

    .footer { position: fixed; bottom: 0; left: 0; right: 0; padding: 8px 28px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 7.5px; color: #94a3b8; }
</style>
</head>
<body>

<div class="doc-header">
    <div>
        <div class="doc-title">Tirage — {{ $event->name }}</div>
        <div class="doc-cat">{{ $draw->category }}</div>
    </div>
    <div class="doc-meta">
        {{ $draw->total_athletes }} athlète(s)<br>
        Généré le {{ $generatedAt }}<br>
        Ligue Régionale de Fatick Taekwondo
    </div>
</div>

@php $winner = $draw->winner; @endphp
@if($winner)
<div class="winner-box">
    <div>
        <div style="font-size:18px;color:#f59e0b;">🏆</div>
    </div>
    <div>
        <div class="winner-lbl">Vainqueur</div>
        <div class="winner-name">{{ $winner['name'] ?? '—' }}</div>
        @if(!empty($winner['club']))
        <div class="winner-club">{{ $winner['club'] }}</div>
        @endif
    </div>
</div>
@endif

@if($draw->use_pools && $draw->pools)

    {{-- ── Pool format ─────────────────────────────────────────────────────── --}}
    @foreach($draw->pools as $pool)
    <div class="pool-block">
        <div class="pool-hdr">{{ $pool['name'] }} — {{ count($pool['athletes'] ?? []) }} athlète(s)</div>
        <table>
            <thead>
                <tr>
                    <th style="width:30px;">N°</th>
                    <th>Athlète 1</th>
                    <th style="width:20px; text-align:center;">vs</th>
                    <th>Athlète 2</th>
                    <th style="width:100px; text-align:center;">Vainqueur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pool['matches'] as $match)
                @php $mWinner = $match['winner'] ?? null; @endphp
                <tr>
                    <td class="match-no">{{ $match['id'] }}</td>
                    <td>
                        @if($match['athlete1'])
                        <div class="ath-name {{ $mWinner && $mWinner['id'] == $match['athlete1']['id'] ? 'winner-cell' : '' }}">{{ $match['athlete1']['name'] }}</div>
                        <div class="ath-club">{{ $match['athlete1']['club'] ?? '' }}</div>
                        @else<span class="bye-lbl">Bye</span>@endif
                    </td>
                    <td class="vs">vs</td>
                    <td>
                        @if($match['athlete2'])
                        <div class="ath-name {{ $mWinner && $mWinner['id'] == $match['athlete2']['id'] ? 'winner-cell' : '' }}">{{ $match['athlete2']['name'] }}</div>
                        <div class="ath-club">{{ $match['athlete2']['club'] ?? '' }}</div>
                        @else<span class="bye-lbl">Bye</span>@endif
                    </td>
                    <td style="text-align:center;">
                        @if($mWinner)
                        <span class="winner-cell">{{ $mWinner['name'] }}</span>
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

@else

    {{-- ── Direct elimination ───────────────────────────────────────────────── --}}
    @foreach($byRound as $roundNum => $roundData)
    <div class="round-block">
        <div class="round-hdr">{{ $roundData['label'] }}</div>
        <table>
            <thead>
                <tr>
                    <th style="width:30px;">N°</th>
                    <th>Athlète 1</th>
                    <th style="width:20px; text-align:center;">vs</th>
                    <th>Athlète 2</th>
                    <th style="width:100px; text-align:center;">Vainqueur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roundData['matches'] as $match)
                @php $mWinner = $match['winner'] ?? null; @endphp
                <tr class="{{ ($match['is_bye'] ?? false) ? 'bye-row' : '' }}">
                    <td class="match-no">{{ $match['id'] ?? '—' }}</td>
                    <td>
                        @if($match['athlete1'])
                        <div class="ath-name {{ $mWinner && ($mWinner['id'] ?? null) == ($match['athlete1']['id'] ?? null) ? 'winner-cell' : '' }}">{{ $match['athlete1']['name'] }}</div>
                        <div class="ath-club">{{ $match['athlete1']['club'] ?? '' }}</div>
                        @elseif($match['is_bye'] ?? false)
                        <span class="bye-lbl">— (bye)</span>
                        @else
                        <span style="color:#cbd5e1;">À déterminer</span>
                        @endif
                    </td>
                    <td class="vs">vs</td>
                    <td>
                        @if($match['athlete2'])
                        <div class="ath-name {{ $mWinner && ($mWinner['id'] ?? null) == ($match['athlete2']['id'] ?? null) ? 'winner-cell' : '' }}">{{ $match['athlete2']['name'] }}</div>
                        <div class="ath-club">{{ $match['athlete2']['club'] ?? '' }}</div>
                        @elseif($match['is_bye'] ?? false)
                        <span class="bye-lbl">— (bye)</span>
                        @else
                        <span style="color:#cbd5e1;">À déterminer</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($mWinner)
                        <span class="winner-cell">{{ $mWinner['name'] }}</span>
                        @elseif($match['is_bye'] ?? false)
                        <span style="color:#94a3b8;font-size:8px;">Qualifié</span>
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

@endif

<div class="footer">
    <span>{{ $event->name }} · {{ $draw->category }}</span>
    <span>Ligue Régionale de Fatick Taekwondo · {{ $generatedAt }}</span>
</div>

</body>
</html>
