<x-public-layout :title="'Classements ' . $season" description="Classements régionaux de la Ligue de Fatick Taekwondo — Saison {{ $season }}">

<div style="background: var(--bg); min-height: 100vh; padding-top: 80px;">

    {{-- Hero --}}
    <div style="background: var(--bg); padding: 4.5rem 0; position: relative; overflow: hidden; border-bottom: 1px solid rgba(255,255,255,0.06);">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 800px; height: 300px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(245,158,11,0.08) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative;">

            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 1.5rem;">
                <span style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.4); letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">01</span>
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Classements régionaux</span>
            </div>

            <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 2rem;">
                <div>
                    <h1 style="font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 700; color: var(--text); line-height: 1.05; letter-spacing: -0.03em; margin: 0 0 0.75rem; font-family: 'Space Grotesk', sans-serif;">
                        Classements — Saison {{ $season }}
                    </h1>
                    <p style="color: var(--t-3); font-size: 0.875rem; margin: 0;">
                        Cumul des points sur toutes les compétitions de la saison
                    </p>
                </div>

                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    {{-- Season selector --}}
                    @if($seasons->count() > 1)
                    <form method="GET" action="{{ route('public.rankings') }}" style="display: flex; align-items: center; gap: 10px;">
                        <label style="color: var(--t-3); font-size: 0.72rem; letter-spacing: 0.08em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Saison</label>
                        <select name="season" onchange="this.form.submit()"
                                style="background: var(--surface); border: 1px solid var(--line); color: var(--text); padding: 8px 14px; font-size: 0.82rem; outline: none; cursor: pointer; font-family: 'Space Grotesk', sans-serif;">
                            @foreach($seasons as $s)
                            <option value="{{ $s }}" {{ $s == $season ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </form>
                    @endif

                    {{-- Export buttons --}}
                    @if(!$byCategory->isEmpty())
                    <a href="{{ route('public.rankings-csv', ['season' => $season]) }}"
                       style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);font-size:0.75rem;font-family:'Space Grotesk',sans-serif;font-weight:600;text-decoration:none;transition:all .15s;"
                       onmouseover="this.style.borderColor='rgba(245,158,11,0.5)';this.style.color='#f59e0b';"
                       onmouseout="this.style.borderColor='rgba(255,255,255,0.12)';this.style.color='rgba(255,255,255,0.6)';">
                        <svg style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        CSV
                    </a>
                    <a href="{{ route('public.rankings-pdf', ['season' => $season]) }}"
                       style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);font-size:0.75rem;font-family:'Space Grotesk',sans-serif;font-weight:600;text-decoration:none;transition:all .15s;"
                       onmouseover="this.style.borderColor='rgba(245,158,11,0.5)';this.style.color='#f59e0b';"
                       onmouseout="this.style.borderColor='rgba(255,255,255,0.12)';this.style.color='rgba(255,255,255,0.6)';">
                        <svg style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        PDF
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    @if($byCategory->isEmpty())
    <div style="padding: 6rem 0; text-align: center;">
        <svg style="width: 48px; height: 48px; color: rgba(255,255,255,0.1); margin: 0 auto 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <p style="color: var(--t-3); font-size: 0.875rem;">Aucun classement disponible pour la saison {{ $season }}.</p>
        @if($seasons->count() > 0)
        <p style="color: var(--t-4); font-size: 0.75rem; margin-top: 8px;">Saisons disponibles :
            @foreach($seasons as $s)
            <a href="{{ route('public.rankings', ['season' => $s]) }}" style="color: #f59e0b; text-decoration: none;">{{ $s }}</a>{{ !$loop->last ? ' · ' : '' }}
            @endforeach
        </p>
        @endif
    </div>
    @else
    <div style="padding: 5rem 0 7rem;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem;">

            @php $catNum = 0; @endphp
            @foreach($byCategory as $categoryKey => $standings)
            @php
                $catNum++;
                $parts   = explode('|', $categoryKey);
                $ageLabel    = $parts[0] ?? $categoryKey;
                $genderRaw   = $parts[1] ?? '';
                $weightLabel = $parts[2] ?? '';
                $genderColor = $genderRaw === 'M' ? '#60a5fa' : ($genderRaw === 'F' ? '#f472b6' : '#94a3b8');
                $genderLabel = match($genderRaw) { 'M' => 'Hommes', 'F' => 'Femmes', default => '' };
                $totalCount  = $standings->count();
                $catId       = 'cat-' . $catNum;
            @endphp

            <div style="margin-bottom: 5rem;">
                {{-- Category header --}}
                <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--line-2);">
                    <div style="width: 2.75rem; height: 2.75rem; background: rgba(245,158,11,0.06); border: 1px solid rgba(245,158,11,0.2); display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 0.9rem; color: #f59e0b; flex-shrink: 0;">
                        {{ str_pad($catNum, 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em;">
                            {{ $ageLabel }}
                            @if($genderLabel)<span style="color: {{ $genderColor }}; font-weight: 600;"> {{ $genderLabel }}</span>@endif
                            @if($weightLabel)<span style="color: rgba(255,255,255,0.5); font-weight: 400;"> — {{ $weightLabel }}</span>@endif
                        </h2>
                        <p style="font-size: 0.65rem; color: var(--t-3); margin: 4px 0 0; text-transform: uppercase; letter-spacing: 0.14em;">{{ $standings->count() }} athlète(s) classé(s)</p>
                    </div>
                    <div style="font-size: 2rem; font-weight: 700; color: rgba(245,158,11,0.12); font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.04em; flex-shrink: 0;">
                        {{ $standings->count() }}
                    </div>
                </div>

                {{-- Rankings table --}}
                <div style="overflow-x: auto; border: 1px solid rgba(255,255,255,0.06);">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 560px;">
                        <thead>
                            <tr style="background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--line-2);">
                                <th style="padding: 11px 16px; text-align: left; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; width: 52px;">Rang</th>
                                <th style="padding: 11px 16px; text-align: left; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em;">Athlète</th>
                                <th style="padding: 11px 16px; text-align: left; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em;">Club</th>
                                <th style="padding: 11px 16px; text-align: center; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; white-space: nowrap;">Pts total</th>
                                <th style="padding: 11px 16px; text-align: center; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; white-space: nowrap;">Victoires</th>
                                <th style="padding: 11px 16px; text-align: center; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; white-space: nowrap;">Compétitions</th>
                                <th style="padding: 11px 16px; text-align: center; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; white-space: nowrap;">Meilleur résultat</th>
                            </tr>
                        </thead>
                        <tbody id="{{ $catId }}-tbody">
                            @foreach($standings as $rank => $entry)
                            @php
                                $position    = $rank + 1;
                                $medalBg     = match($position) { 1 => 'rgba(234,179,8,0.12)', 2 => 'rgba(148,163,184,0.08)', 3 => 'rgba(180,83,9,0.1)', default => 'transparent' };
                                $medalColor  = match($position) { 1 => '#fbbf24', 2 => '#94a3b8', 3 => '#f97316', default => null };
                                $medalLabel  = match($position) { 1 => 'Or', 2 => 'Argent', 3 => 'Bronze', default => null };
                                $bestPos     = $entry['best_position'];
                            @endphp
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); background: {{ $medalBg }}; transition: background 0.15s;{{ $position > 10 ? 'display:none;' : '' }}"
                                class="{{ $position > 10 ? 'rkng-extra' : '' }}"
                                onmouseover="this.style.background='rgba(245,158,11,0.03)'" onmouseout="this.style.background='{{ $medalBg }}'">
                                <td style="padding: 14px 16px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        @if($medalLabel)
                                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: {{ $medalColor }}22; border: 1px solid {{ $medalColor }}44; font-size: 0.7rem; font-weight: 800; color: {{ $medalColor }}; font-family: 'Space Grotesk', sans-serif; flex-shrink: 0;">{{ $position }}</span>
                                        @else
                                        <span style="color: rgba(255,255,255,0.2); font-size: 0.75rem; font-weight: 700; font-family: 'Space Grotesk', sans-serif; width: 28px; text-align: center; flex-shrink: 0;">{{ $position }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 600; color: var(--text); white-space: nowrap;">
                                        {{ $entry['athlete']?->first_name }} {{ $entry['athlete']?->last_name }}
                                    </div>
                                    @if($medalLabel)
                                    <div style="margin-top: 3px;">
                                        <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.6rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: {{ $medalColor }}; font-family: 'Space Grotesk', sans-serif;">
                                            <svg width="8" height="8" viewBox="0 0 24 24" fill="{{ $medalColor }}"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                            {{ $medalLabel }}
                                        </span>
                                    </div>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px; color: var(--t-2);">{{ $entry['athlete']?->club ?? '—' }}</td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <span style="font-size: 1.15rem; font-weight: 800; color: {{ $medalColor ?? '#fff' }}; font-family: 'Space Grotesk', sans-serif;">{{ $entry['total_points'] }}</span>
                                    <span style="font-size: 0.65rem; color: var(--t-3); display: block; letter-spacing: 0.08em;">pts</span>
                                </td>
                                <td style="padding: 14px 16px; text-align: center; color: rgba(255,255,255,0.6); font-family: 'Space Grotesk', sans-serif; font-weight: 600;">{{ $entry['total_wins'] }}</td>
                                <td style="padding: 14px 16px; text-align: center; color: rgba(255,255,255,0.35); font-size: 0.8rem;">{{ $entry['events_count'] }}</td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    @if($bestPos)
                                    @php
                                        $bpColor = match($bestPos) { 1 => '#fbbf24', 2 => '#94a3b8', 3 => '#f97316', default => 'rgba(255,255,255,0.3)' };
                                        $bpLabel = match($bestPos) { 1 => '1er', 2 => '2ème', 3 => '3ème', default => $bestPos.'ème' };
                                    @endphp
                                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; background: {{ $bpColor }}22; border: 1px solid {{ $bpColor }}44; color: {{ $bpColor }}; font-family: 'Space Grotesk', sans-serif;">
                                        {{ $bpLabel }}
                                    </span>
                                    @else
                                    <span style="color: rgba(255,255,255,0.2); font-size: 0.75rem;">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Voir plus / moins --}}
                @if($totalCount > 10)
                <div style="text-align:center; margin-top: 16px;">
                    <button onclick="rkngToggle('{{ $catId }}', {{ $totalCount }}, this)"
                            style="background:transparent;border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.4);padding:8px 20px;font-size:0.72rem;font-family:'Space Grotesk',sans-serif;font-weight:600;cursor:pointer;letter-spacing:0.08em;text-transform:uppercase;transition:all .15s;"
                            onmouseover="this.style.borderColor='rgba(245,158,11,0.4)';this.style.color='#f59e0b';"
                            onmouseout="this.style.borderColor='rgba(255,255,255,0.12)';this.style.color='rgba(255,255,255,0.4)';">
                        Voir les {{ $totalCount - 10 }} autres athlètes
                    </button>
                </div>
                @endif
            </div>
            @endforeach

        </div>
    </div>
    @endif

</div>

<script>
function rkngToggle(catId, total, btn) {
    const rows = document.querySelectorAll('#' + catId + '-tbody .rkng-extra');
    const expanded = rows[0] && rows[0].style.display !== 'none';
    rows.forEach(r => r.style.display = expanded ? 'none' : '');
    btn.textContent = expanded
        ? 'Voir les ' + (total - 10) + ' autres athlètes'
        : 'Réduire';
}
</script>
</x-public-layout>
