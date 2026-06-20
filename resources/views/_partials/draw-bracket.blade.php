{{--
    draw-bracket.blade.php
    Partial pour un seul tirage — rechargeable en AJAX.
    Variables: $draw (Draw), $event (Event), $isAdmin (bool)
--}}
@php
    $rNames = [1=>'Finale',2=>'Demi-finales',3=>'Quarts de finale',4=>'Huitièmes',5=>'16es',6=>'32es',7=>'64es',8=>'128es'];
    $gb = $draw->gender === 'M'
        ? '<span class="g-m">♂ Masculin</span>'
        : '<span class="g-f">♀ Féminin</span>';
    if ($draw->use_pools && $draw->pools) {
        $drawMatchCount = collect($draw->pools['pools'] ?? [])->sum(fn($p) => count($p['matches'])) + count($draw->pools['finals'] ?? []);
        $drawPoolCount  = count($draw->pools['pools'] ?? []);
    } else {
        $drawMatchCount = count($draw->matches ?? []);
        $drawPoolCount  = 0;
    }
@endphp

<div id="draw-bracket-{{ $draw->id }}"
     data-draw-id-poll="{{ $draw->id }}"
     data-draw-updated-at="{{ $draw->updated_at?->toISOString() }}">
<section class="tkb-section" id="cat-{{ $draw->id }}">

{{-- Section header --}}
<div class="sec-hdr">
    <div style="display:flex;align-items:baseline;gap:8px;flex-wrap:wrap;">
        <h2 class="sec-title">{{ $draw->age_category }} {{ $draw->weight_category }}{!! $gb !!}</h2>
        <span class="sec-sub">{{ $draw->total_athletes }} athlètes · {{ $drawMatchCount }} matchs{{ $drawPoolCount ? ' · '.$drawPoolCount.' poule'.($drawPoolCount>1?'s':'') : '' }}</span>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        <a href="{{ route('draws.pdf', $draw->id) }}" target="_blank"
           style="display:inline-flex;align-items:center;gap:6px;padding:6px 13px;border:1px solid var(--border);border-radius:var(--r);font-size:12px;font-weight:600;color:var(--gray);text-decoration:none;transition:all .2s;"
           onmouseover="this.style.borderColor='var(--gold)';this.style.color='var(--gold)';this.style.background='var(--goldbg)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--gray)';this.style.background='transparent'">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            PDF
        </a>
        @if($isAdmin)
        <span class="badge-admin">
            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
            Mode gestion
        </span>
        @endif
        <span class="badge-done">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            Tirage effectué
        </span>
    </div>
</div>

{{-- ============================================================
     FORMAT ÉLIMINATION DIRECTE
     ============================================================ --}}
@if(!$draw->use_pools && $draw->matches)
@php
    $allM = collect($draw->matches);
    $mainM = $allM->where('round', '>=', 1)->sortBy('position');
    $maxR = $mainM->max('round');
    // Split basé sur la POSITION du bracket (pas le count) pour préserver la structure de l'arbre.
    // Round maxR a 2^(maxR-1) positions au total ; la moitié gauche = positions <= halfPos.
    $lCols=[]; $rCols=[];
    $firstRoundSize = (int)pow(2, $maxR - 1);
    for ($r = $maxR; $r >= 2; $r--) {
        $rm = $mainM->where('round', $r)->sortBy('position')->values();
        $halfPos = (int)($firstRoundSize / pow(2, $maxR - $r + 1));
        $lCols[$r] = $rm->filter(fn($m) => ($m['position'] ?? 0) <= $halfPos)->values();
        $rCols[$r] = $rm->filter(fn($m) => ($m['position'] ?? 0) >  $halfPos)->values();
    }
    $rColsOrd = array_reverse($rCols, true);
    $fm       = $mainM->where('round', 1)->first();
    $champ    = ($fm && !empty($fm['winner']) && empty($fm['winner']['placeholder'])) ? ($fm['winner']['name'] ?? null) : null;
    $pf3de    = $allM->first(fn($m) => ($m['round'] ?? -1) === 0);
    $gridOuter   = max(1, (int)($firstRoundSize / 2));
    $bracketMinH = max(400, $gridOuter * 110);
@endphp
<div class="bracket-outer"><div class="bracket" style="min-height:{{ $bracketMinH }}px">
    <div class="side-lbl">{{ $draw->age_category }}</div>
    {{-- LEFT --}}
    <div class="bk-side">
    @foreach($lCols as $rn => $rm)
    @php $halfPos = max(1, (int)($firstRoundSize / pow(2, $maxR - $rn + 1))); @endphp
    <div class="bk-col {{ $loop->first ? 'bk-col--outer' : '' }}">
        <div class="bk-col__lbl">{{ $rNames[$rn] ?? 'Tour' }}</div>
        <div class="bk-col__body">
        @for ($pos = 1; $pos <= $halfPos; $pos++)
        @php
            $m           = $rm->firstWhere('position', $pos);
            $nextM       = ($pos % 2 === 1 && $pos < $halfPos) ? $rm->firstWhere('position', $pos + 1) : null;
            $prevM       = ($pos % 2 === 0 && $pos > 1)        ? $rm->firstWhere('position', $pos - 1) : null;
            $drawBarTop  = ($pos % 2 === 1) && ($pos < $halfPos) && ($m !== null);
            $drawBarBot  = ($pos % 2 === 0) && ($m !== null);
            $barOk       = ($drawBarTop && !empty($m['winner_id'] ?? '') && !empty($nextM['winner_id'] ?? ''))
                        || ($drawBarBot && !empty($m['winner_id'] ?? '') && !empty($prevM['winner_id'] ?? ''));
        @endphp
        <div class="bk-grid-slot {{ $drawBarTop ? 'bk-grid-slot--bar-L' : '' }} {{ $drawBarBot ? 'bk-grid-slot--bar-L-even' : '' }} {{ $barOk ? 'is-resolved' : '' }}">
            @if($m)
            <div class="bk-slot {{ $halfPos === 1 ? 'bk-slot--sL' : 'bk-slot--L' }} {{ !empty($m['winner_id']) ? 'is-resolved' : '' }}">
                @include('_partials.tkb-match-card', ['m' => $m, 'side' => 'L'])
            </div>
            @endif
        </div>
        @endfor
        </div>
    </div>
    @endforeach
    </div>
    {{-- CENTER --}}
    <div class="bk-center">
        <div class="bk-col__lbl bk-center-lbl-ph" aria-hidden="true">&nbsp;</div>
        <div class="bk-center-body">
        <div class="bk-center-top">
            <div class="bk-event-badge">{{ $event->name }}</div>
            <div class="bk-trophy"><svg width="36" height="36" fill="none" stroke="var(--gold)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9H4.5a2.5 2.5 0 010-5H6"/><path d="M18 9h1.5a2.5 2.5 0 000-5H18"/><path d="M4 22h16"/><path d="M10 22v-4a2 2 0 012-2h0a2 2 0 012 2v4"/><path d="M8 6c0 5.523 2.686 10 6 10s6-4.477 6-10H8z"/></svg></div>
            <div class="bk-cat">{{ $draw->age_category }}<br>{{ $draw->weight_category }}{!! $gb !!}</div>
        </div>
        @if($fm)
        @php $fa1=$fm['athlete1']??null;$fa2=$fm['athlete2']??null;$fwId=$fm['winner_id']??null; @endphp
        <div class="bk-finale {{ $fwId ? 'bk-finale--resolved' : '' }}"
             @if($isAdmin && !$fwId) data-match-id="{{ $fm['id'] }}" data-draw-id="{{ $draw->id }}" @endif>
            <div class="bk-finale__lbl">&#9733; Finale &#9733;</div>
            @foreach([[$fa1,1],[$fa2,2]] as [$fa,$fi])
            @if($fa && empty($fa['placeholder']))
            <div class="bk-finale__row {{ ($fwId && $fwId==($fa['id']??null)) ? 'bk-finale__row--win' : '' }} {{ ($isAdmin && !$fwId) ? 'bk-row--clickable' : '' }}"
                 @if($isAdmin && !$fwId) onclick="tkbSetWinner({{ $draw->id }}, {{ $fm['id']??0 }}, {{ $fa['id']??0 }})" @endif>
                <span class="bk-f-seed">{{ $fi }}</span>
                <div style="min-width:0"><div class="bk-finale__name">{{ $fa['name']??'?' }}</div>@if(!empty($fa['club']))<div class="bk-finale__club">{{ $fa['club'] }}</div>@endif</div>
                @if($isAdmin && !$fwId)<span class="bk-set-icon">▶</span>@endif
            </div>
            @else
            <div class="bk-finale__row bk-finale__row--tbd">
                <span class="bk-f-seed" style="border-color:var(--border2);color:var(--gray2)">?</span>
                <div class="bk-finale__name" style="color:var(--gray2);font-style:italic;font-weight:400">En attente</div>
            </div>
            @endif
            @endforeach
            @if($isAdmin && $fwId)
            <button class="bk-reset-finale" onclick="tkbResetWinner({{ $draw->id }}, {{ $fm['id']??0 }})" type="button" title="Annuler">↺ Annuler</button>
            @endif
        </div>
        @endif
        <div class="bk-center-bottom"></div>
        </div>{{-- /bk-center-body --}}
    </div>
    {{-- RIGHT --}}
    <div class="bk-side">
    @foreach($rColsOrd as $rn => $rm)
    @php $halfPos = max(1, (int)($firstRoundSize / pow(2, $maxR - $rn + 1))); @endphp
    <div class="bk-col {{ $loop->last ? 'bk-col--outer' : '' }}">
        <div class="bk-col__lbl">{{ $rNames[$rn] ?? 'Tour' }}</div>
        <div class="bk-col__body">
        @for ($pos = 1; $pos <= $halfPos; $pos++)
        @php
            $bp          = $halfPos + $pos;
            $m           = $rm->firstWhere('position', $bp);
            $nextM       = ($pos % 2 === 1 && $pos < $halfPos) ? $rm->firstWhere('position', $bp + 1) : null;
            $prevM       = ($pos % 2 === 0 && $pos > 1)        ? $rm->firstWhere('position', $bp - 1) : null;
            $drawBarTop  = ($pos % 2 === 1) && ($pos < $halfPos) && ($m !== null);
            $drawBarBot  = ($pos % 2 === 0) && ($m !== null);
            $barOk       = ($drawBarTop && !empty($m['winner_id'] ?? '') && !empty($nextM['winner_id'] ?? ''))
                        || ($drawBarBot && !empty($m['winner_id'] ?? '') && !empty($prevM['winner_id'] ?? ''));
        @endphp
        <div class="bk-grid-slot {{ $drawBarTop ? 'bk-grid-slot--bar-R' : '' }} {{ $drawBarBot ? 'bk-grid-slot--bar-R-even' : '' }} {{ $barOk ? 'is-resolved' : '' }}">
            @if($m)
            <div class="bk-slot {{ $halfPos === 1 ? 'bk-slot--sR' : 'bk-slot--R' }} {{ !empty($m['winner_id']) ? 'is-resolved' : '' }}">
                @include('_partials.tkb-match-card', ['m' => $m, 'side' => 'R'])
            </div>
            @endif
        </div>
        @endfor
        </div>
    </div>
    @endforeach
    </div>
    <div class="side-lbl side-lbl--r">{{ $draw->weight_category }}</div>
</div></div>
@if($champ)<div class="bk-champ"><svg width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9H4.5a2.5 2.5 0 010-5H6"/><path d="M18 9h1.5a2.5 2.5 0 000-5H18"/><path d="M4 22h16"/><path d="M10 22v-4a2 2 0 012-2h0a2 2 0 012 2v4"/><path d="M8 6c0 5.523 2.686 10 6 10s6-4.477 6-10H8z"/></svg><div><div class="bk-champ__lbl">Champion</div><div class="bk-champ__name">{{ $champ }}</div></div></div>@endif

{{-- Match 3e place --}}
@if($pf3de)
@php
    $pf3a1=$pf3de['athlete1']??null; $pf3a2=$pf3de['athlete2']??null;
    $pf3w=$pf3de['winner_id']??null;
    $pf3p1=empty($pf3a1)||!empty($pf3a1['placeholder']);
    $pf3p2=empty($pf3a2)||!empty($pf3a2['placeholder']);
@endphp
<div class="petite-finale">
    <span class="petite-finale__lbl">3<sup>e</sup> place</span>
    <div class="petite-finale__match">
        <span class="petite-finale__name {{ (!$pf3p1&&$pf3w&&$pf3w==($pf3a1['id']??null))?'petite-finale__name--win':'' }}"
              style="{{ $pf3p1?'opacity:.4':'' }}">{{ $pf3p1?'En attente':($pf3a1['name']??'?') }}</span>
        <span class="petite-finale__vs">vs</span>
        <span class="petite-finale__name {{ (!$pf3p2&&$pf3w&&$pf3w==($pf3a2['id']??null))?'petite-finale__name--win':'' }}"
              style="{{ $pf3p2?'opacity:.4':'' }}">{{ $pf3p2?'En attente':($pf3a2['name']??'?') }}</span>
    </div>
    @if($isAdmin && !$pf3w && !$pf3p1 && !$pf3p2)
    <div class="petite-finale__admin">
        <button class="pf3-btn" onclick="tkbSetWinner({{ $draw->id }}, {{ $pf3de['id']??0 }}, {{ $pf3a1['id']??0 }})">{{ $pf3a1['name']??'' }}</button>
        <button class="pf3-btn" onclick="tkbSetWinner({{ $draw->id }}, {{ $pf3de['id']??0 }}, {{ $pf3a2['id']??0 }})">{{ $pf3a2['name']??'' }}</button>
    </div>
    @endif
</div>
@endif

{{-- ============================================================
     FORMAT POULES → tables de poule EN PREMIER, puis bracket finale
     ============================================================ --}}
@elseif($draw->use_pools && $draw->pools)
@php
    $pools = $draw->pools['pools']  ?? [];
    $pf3   = collect($draw->pools['finals'] ?? [])->first(fn($m) => ($m['round'] ?? -1) === 0);
    $fAll  = collect($draw->pools['finals'] ?? [])->filter(fn($m) => ($m['round'] ?? -1) >= 1);

    // Lookup "1er Poule X" / "2ème Poule X" → athlète réel
    $_pl = [];
    foreach ($pools as $_i => $_p) {
        $_l = chr(65 + $_i);
        if (!empty($_p['winner']))    $_pl['1er Poule '.$_l]  = $_p['winner'];
        if (!empty($_p['runner_up'])) $_pl['2ème Poule '.$_l] = $_p['runner_up'];
    }
    $ra = fn($a) => ($a && !empty($a['placeholder']) && isset($_pl[$a['name'] ?? '']))
        ? array_merge($_pl[$a['name']], ['placeholder' => false, 'seed' => $a['seed'] ?? null])
        : $a;

    $fAllR = $fAll->map(fn($m) => array_merge($m, [
        'athlete1' => $ra($m['athlete1'] ?? null),
        'athlete2' => $ra($m['athlete2'] ?? null),
        'winner'   => $ra($m['winner']   ?? null),
    ]));

    $maxR = $fAllR->isNotEmpty() ? $fAllR->max('round') : 0;
    $lCols = []; $rCols = [];
    $_frs = $maxR > 0 ? (int)pow(2, $maxR - 1) : 1;
    for ($r = $maxR; $r >= 2; $r--) {
        $rm = $fAllR->where('round', $r)->sortBy('position')->values();
        $_hp = (int)($_frs / pow(2, $maxR - $r + 1));
        $lCols[$r] = $rm->filter(fn($m) => ($m['position'] ?? 0) <= $_hp)->values();
        $rCols[$r] = $rm->filter(fn($m) => ($m['position'] ?? 0) >  $_hp)->values();
    }
    $rColsOrd = array_reverse($rCols, true);
    $fm    = $fAllR->where('round', 1)->first();
    $champ = ($fm && !empty($fm['winner']) && empty($fm['winner']['placeholder'])) ? ($fm['winner']['name'] ?? null) : null;

    // Vérifier si toutes les poules sont terminées
    $allPoolsDone = !empty($pools) && collect($pools)->every(fn($p) => collect($p['matches'])->every(fn($m) => !empty($m['winner_id'])));

    // Pool columns for bracket visualization — group pools by the QF match they feed into
    $_pc = ['#3b82f6','#f59e0b','#10b981','#ef4444','#8b5cf6','#ec4899','#06b6d4','#84cc16'];
    $_qfRaw  = collect($draw->pools['finals'] ?? [])->filter(fn($_fm) => ($_fm['round'] ?? 0) === $maxR)->sortBy('position')->values();
    $_qfH    = intdiv($_qfRaw->count(), 2);
    $_xpL2   = fn($_n) => preg_match('/Poule ([A-Z])/', (string)($_n ?? ''), $_xm3) ? $_xm3[1] : null;
    $_pmap3  = [];
    foreach ($pools as $_pi3 => $_pp3) {
        $_pmap3[chr(65 + $_pi3)] = ['d' => $_pp3, 'i' => $_pi3, 'l' => chr(65 + $_pi3)];
    }
    $_buildG3 = function ($_slice3) use ($_xpL2, $_pmap3) {
        $_grps3 = [];
        foreach ($_slice3 as $_qm3) {
            $_l1 = $_xpL2($_qm3['athlete1']['name'] ?? '');
            $_l2 = $_xpL2($_qm3['athlete2']['name'] ?? '');
            $_g3 = [];
            if ($_l1 && isset($_pmap3[$_l1])) $_g3[] = $_pmap3[$_l1];
            if ($_l2 && isset($_pmap3[$_l2])) $_g3[] = $_pmap3[$_l2];
            if ($_g3) $_grps3[] = $_g3;
        }
        return $_grps3;
    };
    $_lpGrps = $_buildG3($_qfRaw->take($_qfH));
    $_rpGrps = $_buildG3($_qfRaw->slice($_qfH));
    $_outerCount  = max(1, isset($lCols[$maxR]) ? $lCols[$maxR]->count() : 1, isset($rCols[$maxR]) ? $rCols[$maxR]->count() : 1);
    $_bracketMinH = max(400, $_outerCount * 110);
@endphp

{{-- ── 1. TABLES DE POULES (EN PREMIER) ─────────────────────── --}}
<div class="pools-section pools-section--top">
<div class="pools-phase-hdr">
    <div class="pools-phase-icon">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    </div>
    <div>
        <div class="pools-phase-title">Phase de poules</div>
        <div class="pools-phase-sub">{{ count($pools) }} poule{{ count($pools)>1?'s':'' }} · Les qualifiés avancent en phase finale</div>
    </div>
    @if($allPoolsDone)
    <span class="pools-phase-done">✓ Terminé</span>
    @endif
</div>
<div class="pools-grid">
@foreach($pools as $pi => $pool)
@php
    $wins = [];
    foreach ($pool['athletes'] as $a) { $wins[$a['id']] = 0; }
    foreach ($pool['matches'] as $pm) {
        if (!empty($pm['winner_id'])) $wins[$pm['winner_id']] = ($wins[$pm['winner_id']] ?? 0) + 1;
    }
    $ranked = $pool['athletes'];
    usort($ranked, fn($a, $b) => ($wins[$b['id']] ?? 0) <=> ($wins[$a['id']] ?? 0));
    $poolLetter = chr(65 + $pi);
    $poolDone   = collect($pool['matches'])->every(fn($m) => !empty($m['winner_id']));
    $poolColors = ['#3b82f6','#f59e0b','#10b981','#ef4444','#8b5cf6','#ec4899','#06b6d4','#84cc16'];
    $poolColor  = $poolColors[$pi % count($poolColors)];
@endphp
<div class="pool-box {{ $poolDone ? 'pool-box--done' : '' }}">
    <div class="pool-box__hdr" style="border-left:4px solid {{ $poolColor }};">
        <div style="display:flex;align-items:center;gap:8px;">
            <span class="pool-letter-badge" style="background:{{ $poolColor }}20;color:{{ $poolColor }};border:1px solid {{ $poolColor }}40;">{{ $pool['name'] }}</span>
            <span class="pool-box__cnt">{{ count($pool['athletes']) }} athlètes</span>
        </div>
        @if($poolDone)<span class="pool-box__done">✓ Complète</span>@endif
    </div>
    {{-- Athletes list --}}
    <div class="pool-athletes">
        @foreach($pool['athletes'] as $ai => $ath)
        @php $isQualified = $ai === 0 && $poolDone || (!$poolDone && $ai < 2); @endphp
        <div class="pool-athlete-row {{ $ai === 0 && !empty($wins[$ath['id']]) ? 'pool-athlete-row--leader' : '' }}">
            <span class="pool-athlete-rank" style="color:{{ $poolColor }}">{{ $ai+1 }}</span>
            <div style="min-width:0;flex:1">
                <div class="pool-aname">{{ $ath['name'] }}</div>
                <div class="pool-aclub">{{ $ath['club'] ?? '' }}</div>
            </div>
            @if($poolDone)
            <span class="pool-wins-badge {{ ($wins[$ath['id']]??0)>0 ? 'pool-wins-badge--pos' : '' }}">{{ $wins[$ath['id']]??0 }}V</span>
            @endif
        </div>
        @endforeach
    </div>
    {{-- Pool matches --}}
    <div class="pool-matches">
        <h4 class="pool-mtitle">Matchs de poule</h4>
        @foreach($pool['matches'] as $pm)
        @php
            $hw=$pm['winner_id']??null;
            $a1w=$hw&&$hw==($pm['athlete1']['id']??null);
            $a2w=$hw&&$hw==($pm['athlete2']['id']??null);
        @endphp
        <div class="pool-match {{ $hw?'pool-match--done':'' }}">
            <span class="pool-mnum">{{ $pm['position']??'' }}</span>
            <span class="pool-mname {{ $a1w?'pool-mname--w':'' }}">{{ $pm['athlete1']['name']??'?' }}</span>
            <span class="pool-mvs">vs</span>
            <span class="pool-mname {{ $a2w?'pool-mname--w':'' }}">{{ $pm['athlete2']['name']??'?' }}</span>
            @if($isAdmin && !$hw)
            <span class="pool-match-btns">
                <button class="pmb" onclick="tkbSetWinner({{ $draw->id }}, {{ $pm['id']??0 }}, {{ $pm['athlete1']['id']??0 }})" title="Victoire {{ $pm['athlete1']['name']??'' }}">1</button>
                <button class="pmb" onclick="tkbSetWinner({{ $draw->id }}, {{ $pm['id']??0 }}, {{ $pm['athlete2']['id']??0 }})" title="Victoire {{ $pm['athlete2']['name']??'' }}">2</button>
            </span>
            @elseif($isAdmin && $hw)
            <button class="pmb pmb--reset" onclick="tkbResetWinner({{ $draw->id }}, {{ $pm['id']??0 }})" title="Annuler">↺</button>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endforeach
</div>
</div>

{{-- ── 2. BRACKET PHASE FINALE (EN SECOND) ──────────────────── --}}
@if($fAllR->isNotEmpty())
<div class="finale-phase-wrapper">
    <div class="finale-phase-hdr">
        <div class="pools-phase-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9H4.5a2.5 2.5 0 010-5H6"/><path d="M18 9h1.5a2.5 2.5 0 000-5H18"/><path d="M4 22h16"/><path d="M10 22v-4a2 2 0 012-2h0a2 2 0 012 2v4"/><path d="M8 6c0 5.523 2.686 10 6 10s6-4.477 6-10H8z"/></svg>
        </div>
        <div>
            <div class="pools-phase-title">Phase finale</div>
            <div class="pools-phase-sub">
                @if(!$allPoolsDone)
                    <span style="color:var(--gold);font-size:11px;">⏳ En attente de la fin des poules</span>
                @else
                    Les qualifiés s'affrontent en élimination directe
                @endif
            </div>
        </div>
    </div>

<div class="bracket-outer"><div class="bracket" style="min-height:{{ $_bracketMinH }}px">
    <div class="side-lbl">{{ $draw->age_category }}</div>
    {{-- LEFT POOL COLUMNS --}}
    @if(!empty($_lpGrps))
    <div class="bk-pool-outer bk-pool-outer--L">
    @foreach($_lpGrps as $_lg3)
    <div class="bk-pool-grp">
        @foreach($_lg3 as $_lpd3)
        @php $_lc3 = $_pc[$_lpd3['i'] % count($_pc)]; $_lw3 = $_lpd3['d']['winner']['id'] ?? null; @endphp
        <div class="bk-pool-mc" style="border-left:3px solid {{ $_lc3 }}">
            <div class="bk-pool-mc__lbl" style="color:{{ $_lc3 }}">Poule {{ $_lpd3['l'] }}</div>
            @foreach($_lpd3['d']['athletes'] as $_ja3)
            <div class="bk-pool-mc__ath{{ ($_lw3&&($_ja3['id']??null)==$_lw3)?' bk-pool-mc__ath--q':'' }}">{{ $_ja3['name'] }}</div>
            @endforeach
        </div>
        @endforeach
    </div>
    @endforeach
    </div>
    @endif
    {{-- LEFT --}}
    <div class="bk-side">
    @foreach($lCols as $rn => $rm)
    <div class="bk-col {{ $loop->first ? 'bk-col--outer' : '' }}">
        <div class="bk-col__lbl">{{ $rNames[$rn] ?? 'Tour' }}</div>
        <div class="bk-col__body">
        <div class="bk-spacer"></div>
        @foreach($rm as $idx => $m)
        @php
            $isSingle   = $rm->count() === 1;
            $isPairTop  = $idx % 2 === 0;
            $isLast     = $idx === $rm->count() - 1;
            $nextM      = !$isLast ? $rm->get($idx + 1) : null;
            $gsResolved = $isPairTop && !$isLast && !empty($m['winner_id']) && !empty($nextM['winner_id'] ?? '');
        @endphp
        <div class="bk-slot {{ $isSingle ? 'bk-slot--sL' : 'bk-slot--L' }} {{ !empty($m['winner_id']) ? 'is-resolved' : '' }}">
            @include('_partials.tkb-match-card', ['m' => $m, 'side' => 'L'])
        </div>
        @if(!$isLast)
            @if($isPairTop)
            <div class="bk-game-spacer bk-game-spacer--L {{ $gsResolved ? 'is-resolved' : '' }}"></div>
            @else
            <div class="bk-inter-spacer"></div>
            @endif
        @endif
        @endforeach
        <div class="bk-spacer"></div>
        </div>
    </div>
    @endforeach
    </div>
    {{-- CENTER --}}
    <div class="bk-center">
        <div class="bk-col__lbl bk-center-lbl-ph" aria-hidden="true">&nbsp;</div>
        <div class="bk-center-body">
        <div class="bk-center-top">
            <div class="bk-event-badge">{{ $event->name }}</div>
            <div class="bk-trophy"><svg width="36" height="36" fill="none" stroke="var(--gold)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9H4.5a2.5 2.5 0 010-5H6"/><path d="M18 9h1.5a2.5 2.5 0 000-5H18"/><path d="M4 22h16"/><path d="M10 22v-4a2 2 0 012-2h0a2 2 0 012 2v4"/><path d="M8 6c0 5.523 2.686 10 6 10s6-4.477 6-10H8z"/></svg></div>
            <div class="bk-cat">{{ $draw->age_category }}<br>{{ $draw->weight_category }}{!! $gb !!}</div>
        </div>
        @if($fm)
        @php $fa1=$fm['athlete1']??null;$fa2=$fm['athlete2']??null;$fwId=$fm['winner_id']??null; @endphp
        <div class="bk-finale {{ $fwId ? 'bk-finale--resolved' : '' }}">
            <div class="bk-finale__lbl">&#9733; Finale &#9733;</div>
            @foreach([[$fa1,1],[$fa2,2]] as [$fa,$fi])
            @if($fa && empty($fa['placeholder']))
            <div class="bk-finale__row {{ ($fwId && $fwId==($fa['id']??null)) ? 'bk-finale__row--win' : '' }} {{ ($isAdmin && !$fwId) ? 'bk-row--clickable' : '' }}"
                 @if($isAdmin && !$fwId) onclick="tkbSetWinner({{ $draw->id }}, {{ $fm['id']??0 }}, {{ $fa['id']??0 }})" @endif>
                <span class="bk-f-seed">{{ $fi }}</span>
                <div style="min-width:0"><div class="bk-finale__name">{{ $fa['name']??'?' }}</div>@if(!empty($fa['club']))<div class="bk-finale__club">{{ $fa['club'] }}</div>@endif</div>
                @if($isAdmin && !$fwId)<span class="bk-set-icon">▶</span>@endif
            </div>
            @else
            <div class="bk-finale__row bk-finale__row--tbd">
                <span class="bk-f-seed" style="border-color:var(--border2);color:var(--gray2)">?</span>
                <div class="bk-finale__name" style="color:var(--gray2);font-style:italic;font-weight:400">En attente</div>
            </div>
            @endif
            @endforeach
            @if($isAdmin && $fwId)
            <button class="bk-reset-finale" onclick="tkbResetWinner({{ $draw->id }}, {{ $fm['id']??0 }})" type="button" title="Annuler">↺ Annuler</button>
            @endif
        </div>
        @endif
        <div class="bk-center-bottom"></div>
        </div>{{-- /bk-center-body --}}
    </div>
    {{-- RIGHT --}}
    <div class="bk-side">
    @foreach($rColsOrd as $rn => $rm)
    <div class="bk-col {{ $loop->last ? 'bk-col--outer' : '' }}">
        <div class="bk-col__lbl">{{ $rNames[$rn] ?? 'Tour' }}</div>
        <div class="bk-col__body">
        <div class="bk-spacer"></div>
        @foreach($rm as $idx => $m)
        @php
            $isSingle   = $rm->count() === 1;
            $isPairTop  = $idx % 2 === 0;
            $isLast     = $idx === $rm->count() - 1;
            $nextM      = !$isLast ? $rm->get($idx + 1) : null;
            $gsResolved = $isPairTop && !$isLast && !empty($m['winner_id']) && !empty($nextM['winner_id'] ?? '');
        @endphp
        <div class="bk-slot {{ $isSingle ? 'bk-slot--sR' : 'bk-slot--R' }} {{ !empty($m['winner_id']) ? 'is-resolved' : '' }}">
            @include('_partials.tkb-match-card', ['m' => $m, 'side' => 'R'])
        </div>
        @if(!$isLast)
            @if($isPairTop)
            <div class="bk-game-spacer bk-game-spacer--R {{ $gsResolved ? 'is-resolved' : '' }}"></div>
            @else
            <div class="bk-inter-spacer"></div>
            @endif
        @endif
        @endforeach
        <div class="bk-spacer"></div>
        </div>
    </div>
    @endforeach
    </div>
    {{-- RIGHT POOL COLUMNS --}}
    @if(!empty($_rpGrps))
    <div class="bk-pool-outer bk-pool-outer--R">
    @foreach($_rpGrps as $_rg3)
    <div class="bk-pool-grp">
        @foreach($_rg3 as $_rpd3)
        @php $_rc3 = $_pc[$_rpd3['i'] % count($_pc)]; $_rw3 = $_rpd3['d']['winner']['id'] ?? null; @endphp
        <div class="bk-pool-mc" style="border-right:3px solid {{ $_rc3 }}">
            <div class="bk-pool-mc__lbl" style="color:{{ $_rc3 }}">Poule {{ $_rpd3['l'] }}</div>
            @foreach($_rpd3['d']['athletes'] as $_ja4)
            <div class="bk-pool-mc__ath{{ ($_rw3&&($_ja4['id']??null)==$_rw3)?' bk-pool-mc__ath--q':'' }}">{{ $_ja4['name'] }}</div>
            @endforeach
        </div>
        @endforeach
    </div>
    @endforeach
    </div>
    @endif
    <div class="side-lbl side-lbl--r">{{ $draw->weight_category }}</div>
</div></div>
@if($champ)<div class="bk-champ"><svg width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9H4.5a2.5 2.5 0 010-5H6"/><path d="M18 9h1.5a2.5 2.5 0 000-5H18"/><path d="M4 22h16"/><path d="M10 22v-4a2 2 0 012-2h0a2 2 0 012 2v4"/><path d="M8 6c0 5.523 2.686 10 6 10s6-4.477 6-10H8z"/></svg><div><div class="bk-champ__lbl">Champion</div><div class="bk-champ__name">{{ $champ }}</div></div></div>@endif

{{-- Match 3e place --}}
@if($pf3)
@php
    $pf3a1=$ra($pf3['athlete1']??null); $pf3a2=$ra($pf3['athlete2']??null);
    $pf3w=$pf3['winner_id']??null;
    $pf3p1=empty($pf3a1)||!empty($pf3a1['placeholder']);
    $pf3p2=empty($pf3a2)||!empty($pf3a2['placeholder']);
@endphp
<div class="petite-finale">
    <span class="petite-finale__lbl">3<sup>e</sup> place</span>
    <div class="petite-finale__match">
        <span class="petite-finale__name {{ (!$pf3p1&&$pf3w&&$pf3w==($pf3a1['id']??null))?'petite-finale__name--win':'' }}"
              style="{{ $pf3p1?'opacity:.4':'' }}">{{ $pf3p1?'En attente':($pf3a1['name']??'?') }}</span>
        <span class="petite-finale__vs">vs</span>
        <span class="petite-finale__name {{ (!$pf3p2&&$pf3w&&$pf3w==($pf3a2['id']??null))?'petite-finale__name--win':'' }}"
              style="{{ $pf3p2?'opacity:.4':'' }}">{{ $pf3p2?'En attente':($pf3a2['name']??'?') }}</span>
    </div>
    @if($isAdmin && !$pf3w && !$pf3p1 && !$pf3p2)
    <div class="petite-finale__admin">
        <button class="pf3-btn" onclick="tkbSetWinner({{ $draw->id }}, {{ $pf3['id']??0 }}, {{ $pf3a1['id']??0 }})">{{ $pf3a1['name']??'' }}</button>
        <button class="pf3-btn" onclick="tkbSetWinner({{ $draw->id }}, {{ $pf3['id']??0 }}, {{ $pf3a2['id']??0 }})">{{ $pf3a2['name']??'' }}</button>
    </div>
    @endif
</div>
@endif

</div>{{-- /finale-phase-wrapper --}}
@endif

@endif

</section>
</div>
