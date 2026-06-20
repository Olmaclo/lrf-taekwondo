<x-app-layout>
<x-slot name="header">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <h2 style="font-size:1.1rem;font-weight:700;margin:0;">Pesée — {{ $event->name }}</h2>
            <p style="font-size:12px;color:#64748b;margin:4px 0 0;">Interface de gestion de la pesée officielle</p>
        </div>
        <a href="{{ route('public.athlete-list', $event->slug) }}" target="_blank"
           style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;border:1px solid #e2e8f0;padding:6px 14px;border-radius:6px;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            Liste publique
        </a>
    </div>
</x-slot>

{{-- Stats bar --}}
<div style="display:flex;gap:14px;flex-wrap:wrap;margin-bottom:24px;">
    <div id="wi-stat-total" style="display:flex;flex-direction:column;align-items:center;padding:14px 24px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;min-width:80px;">
        <span style="font-size:22px;font-weight:800;color:#1e293b;">{{ $stats['total'] }}</span>
        <span style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-top:2px;">Athlètes</span>
    </div>
    <div id="wi-stat-passed" style="display:flex;flex-direction:column;align-items:center;padding:14px 24px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;min-width:80px;">
        <span style="font-size:22px;font-weight:800;color:#16a34a;">{{ $stats['passed'] }}</span>
        <span style="font-size:11px;color:#16a34a;text-transform:uppercase;letter-spacing:.5px;margin-top:2px;">Réussis</span>
    </div>
    <div id="wi-stat-failed" style="display:flex;flex-direction:column;align-items:center;padding:14px 24px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;min-width:80px;">
        <span style="font-size:22px;font-weight:800;color:#dc2626;">{{ $stats['failed'] }}</span>
        <span style="font-size:11px;color:#dc2626;text-transform:uppercase;letter-spacing:.5px;margin-top:2px;">Hors cat.</span>
    </div>
    <div id="wi-stat-pending" style="display:flex;flex-direction:column;align-items:center;padding:14px 24px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;min-width:80px;">
        <span style="font-size:22px;font-weight:800;color:#64748b;">{{ $stats['pending'] }}</span>
        <span style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-top:2px;">En attente</span>
    </div>
    @if($stats['total'] > 0)
    <div style="flex:1;min-width:200px;display:flex;flex-direction:column;justify-content:center;gap:6px;">
        <div style="display:flex;justify-content:space-between;font-size:11px;color:#64748b;">
            <span>Progression pesée</span>
            <span id="wi-progress-pct">{{ round(($stats['passed'] + $stats['failed']) / $stats['total'] * 100) }}%</span>
        </div>
        <div style="height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;display:flex;">
            <div id="wi-bar-passed" style="height:100%;background:#16a34a;transition:width .4s;width:{{ round($stats['passed'] / $stats['total'] * 100) }}%"></div>
            <div id="wi-bar-failed" style="height:100%;background:#dc2626;transition:width .4s;width:{{ round($stats['failed'] / $stats['total'] * 100) }}%"></div>
        </div>
    </div>
    @endif
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Filtre par genre --}}
@if($athletes->isNotEmpty())
<div style="display:flex;align-items:center;gap:8px;margin-bottom:18px;flex-wrap:wrap;">
    <span style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Genre :</span>
    <button onclick="wiFilterGender('all')" id="wi-filter-all"
        style="padding:5px 16px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid #6366f1;background:#6366f1;color:#fff;transition:all .15s;">
        Tous
    </button>
    <button onclick="wiFilterGender('M')" id="wi-filter-M"
        style="padding:5px 16px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid #3b82f6;background:#fff;color:#3b82f6;transition:all .15s;">
        Masculin
    </button>
    <button onclick="wiFilterGender('F')" id="wi-filter-F"
        style="padding:5px 16px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid #ec4899;background:#fff;color:#ec4899;transition:all .15s;">
        Féminin
    </button>
</div>
@endif

@if($athletes->isEmpty())
<div style="text-align:center;padding:4rem;color:#94a3b8;">Aucun athlète validé pour cet événement.</div>
@else
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <th style="padding:8px 18px;text-align:left;font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Athlète</th>
                <th style="padding:8px 12px;text-align:left;font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Club</th>
                <th style="padding:8px 12px;text-align:left;font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Catégorie</th>
                <th style="padding:8px 12px;text-align:center;font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Poids réel (kg)</th>
                <th style="padding:8px 18px;text-align:right;font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Statut</th>
            </tr>
        </thead>
        <tbody>
        @foreach($athletes as $ath)
        @php
            $range  = $allCategories[$ath->age_category][$ath->gender][$ath->weight_category] ?? null;
            $gLabel = \App\Models\Athlete::genderLabel($ath->gender, $ath->age_category);
        @endphp
        <tr id="wi-row-{{ $ath->id }}" data-gender="{{ $ath->gender }}"
            style="border-bottom:1px solid #f1f5f9;transition:background .15s;"
            class="{{ $ath->weigh_in_status === 'passed' ? 'wi-row-passed' : ($ath->weigh_in_status === 'failed' ? 'wi-row-failed' : '') }}">
            <td style="padding:10px 18px;">
                <div style="font-weight:600;color:#1e293b;">{{ $ath->full_name }}</div>
                <div style="font-size:11px;color:#94a3b8;">#{{ $ath->id }}{{ $ath->license_number ? ' · '.$ath->license_number : '' }}</div>
            </td>
            <td style="padding:10px 12px;color:#475569;max-width:140px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $ath->club }}</td>
            <td style="padding:10px 12px;">
                <div style="font-size:12px;font-weight:600;color:#1e293b;">{{ $ath->age_category }} {{ $ath->weight_category }}</div>
                <span style="font-size:11px;padding:2px 8px;border-radius:10px;font-weight:600;
                    background:{{ $ath->gender==='M' ? 'rgba(59,130,246,.1)' : 'rgba(236,72,153,.1)' }};
                    color:{{ $ath->gender==='M' ? '#3b82f6' : '#ec4899' }};">{{ $gLabel }}</span>
            </td>
            <td style="padding:6px 12px;text-align:center;">
                <input type="number" step="0.1" min="10" max="250"
                       id="wi-w-{{ $ath->id }}"
                       value="{{ $ath->weigh_in_weight ?? '' }}"
                       placeholder="{{ $range ? ($range[0] == 0 ? '<'.$range[1] : $range[0].'-'.$range[1]) : '—' }}"
                       style="width:80px;padding:5px 8px;border:1px solid #e2e8f0;border-radius:6px;font-size:12px;text-align:center;outline:none;transition:border-color .15s;"
                       onfocus="this.style.borderColor='#6366f1'"
                       onblur="this.style.borderColor='#e2e8f0'">
            </td>
            <td style="padding:10px 18px;text-align:right;">
                <div id="wi-status-{{ $ath->id }}" style="display:inline-flex;align-items:center;gap:6px;">
                @if($ath->weigh_in_status === 'passed')
                    <span class="wi-badge wi-badge--passed">✓ Réussi</span>
                    <button class="wi-btn wi-btn--reset" onclick="wiReset({{ $ath->id }})" title="Annuler">↺</button>
                @elseif($ath->weigh_in_status === 'failed')
                    <span class="wi-badge wi-badge--failed">✗ Hors cat.</span>
                    <button class="wi-btn wi-btn--reset" onclick="wiReset({{ $ath->id }})" title="Annuler">↺</button>
                @else
                    <button class="wi-btn wi-btn--pass" onclick="wiDeclare({{ $ath->id }}, 'passed')">✓ Réussi</button>
                    <button class="wi-btn wi-btn--fail" onclick="wiDeclare({{ $ath->id }}, 'failed')">✗ Hors cat.</button>
                @endif
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif

<style>
.wi-badge{display:inline-flex;align-items:center;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;}
.wi-badge--passed{background:#dcfce7;color:#16a34a;}
.wi-badge--failed{background:#fee2e2;color:#dc2626;}
.wi-btn{padding:5px 13px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;border:none;transition:all .15s;font-family:inherit;}
.wi-btn--pass{background:#dcfce7;color:#16a34a;}
.wi-btn--pass:hover{background:#16a34a;color:#fff;}
.wi-btn--fail{background:#fee2e2;color:#dc2626;}
.wi-btn--fail:hover{background:#dc2626;color:#fff;}
.wi-btn--reset{background:#f1f5f9;color:#64748b;padding:4px 8px;font-size:11px;}
.wi-btn--reset:hover{background:#dc2626;color:#fff;}
.wi-row-passed{background:#f0fdf4!important;}
.wi-row-failed{background:#fef2f2!important;}
</style>

<script>
const WI_CSRF = document.querySelector('meta[name="csrf-token"]').content;
let wiPassed = {{ $stats['passed'] }};
let wiFailed = {{ $stats['failed'] }};
const wiTotal = {{ $stats['total'] }};

function wiUpdateStats() {
    const pending = wiTotal - wiPassed - wiFailed;
    document.getElementById('wi-stat-passed').querySelector('span').textContent = wiPassed;
    document.getElementById('wi-stat-failed').querySelector('span').textContent = wiFailed;
    document.getElementById('wi-stat-pending').querySelector('span').textContent = pending;
    const pPassed = wiTotal ? Math.round(wiPassed / wiTotal * 100) : 0;
    const pFailed = wiTotal ? Math.round(wiFailed / wiTotal * 100) : 0;
    const barP = document.getElementById('wi-bar-passed');
    const barF = document.getElementById('wi-bar-failed');
    if (barP) barP.style.width = pPassed + '%';
    if (barF) barF.style.width = pFailed + '%';
    const pctEl = document.getElementById('wi-progress-pct');
    if (pctEl) pctEl.textContent = wiTotal ? Math.round((wiPassed + wiFailed) / wiTotal * 100) + '%' : '0%';
}

async function wiDeclare(athleteId, status) {
    const w = document.getElementById('wi-w-' + athleteId)?.value;
    const body = { status };
    if (w) body.actual_weight = parseFloat(w);

    const r = await fetch('/api/athletes/' + athleteId + '/weigh-in', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': WI_CSRF },
        body: JSON.stringify(body),
    });
    const d = await r.json();
    if (!d.success) { alert(d.message ?? 'Erreur'); return; }

    const row = document.getElementById('wi-row-' + athleteId);
    row.classList.remove('wi-row-passed', 'wi-row-failed');
    row.classList.add(d.status === 'passed' ? 'wi-row-passed' : 'wi-row-failed');

    const cell = document.getElementById('wi-status-' + athleteId);
    const badgeClass = d.status === 'passed' ? 'wi-badge--passed' : 'wi-badge--failed';
    const badgeText  = d.status === 'passed' ? '✓ Réussi' : '✗ Hors cat.';
    cell.innerHTML = `<span class="wi-badge ${badgeClass}">${badgeText}</span>
        <button class="wi-btn wi-btn--reset" onclick="wiReset(${athleteId})" title="Annuler">↺</button>`;

    if (d.status === 'passed') wiPassed++; else wiFailed++;
    wiUpdateStats();
}

async function wiReset(athleteId) {
    const r = await fetch('/api/athletes/' + athleteId + '/weigh-in/reset', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': WI_CSRF },
    });
    const d = await r.json();
    if (!d.success) { alert(d.message ?? 'Erreur'); return; }

    const row = document.getElementById('wi-row-' + athleteId);
    const wasPassed = row.classList.contains('wi-row-passed');
    row.classList.remove('wi-row-passed', 'wi-row-failed');

    const cell = document.getElementById('wi-status-' + athleteId);
    cell.innerHTML = `<button class="wi-btn wi-btn--pass" onclick="wiDeclare(${athleteId}, 'passed')">✓ Réussi</button>
        <button class="wi-btn wi-btn--fail" onclick="wiDeclare(${athleteId}, 'failed')">✗ Hors cat.</button>`;

    if (wasPassed) wiPassed--; else wiFailed--;
    wiUpdateStats();
}

let wiCurrentGender = 'all';
function wiFilterGender(gender) {
    wiCurrentGender = gender;
    document.querySelectorAll('tbody tr[data-gender]').forEach(row => {
        row.style.display = (gender === 'all' || row.dataset.gender === gender) ? '' : 'none';
    });
    ['all', 'M', 'F'].forEach(g => {
        const btn = document.getElementById('wi-filter-' + g);
        if (!btn) return;
        const active = g === gender;
        if (g === 'all') { btn.style.background = active ? '#6366f1' : '#fff'; btn.style.color = active ? '#fff' : '#6366f1'; }
        if (g === 'M')   { btn.style.background = active ? '#3b82f6' : '#fff'; btn.style.color = active ? '#fff' : '#3b82f6'; }
        if (g === 'F')   { btn.style.background = active ? '#ec4899' : '#fff'; btn.style.color = active ? '#fff' : '#ec4899'; }
    });
}
</script>
</x-app-layout>
