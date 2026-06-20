{{--
    Match card partial — uses bk-* CSS classes defined in draws.blade.php
    Variables: $m (match array), $side ('L' or 'R'), $draw (Draw), $isAdmin (bool)
--}}
@php
    $a1     = $m['athlete1'] ?? null;
    $a2     = $m['athlete2'] ?? null;
    $wId    = $m['winner_id'] ?? null;
    $isBye  = $m['is_bye'] ?? false;
    $pos    = $m['position'] ?? 0;
    $ph1    = !$a1 || !empty($a1['placeholder']);
    $ph2    = !$a2 || !empty($a2['placeholder']);
    $s1     = $a1['seed'] ?? (string)(($pos - 1) * 2 + 1);
    $s2     = $a2['seed'] ?? (string)(($pos - 1) * 2 + 2);
    $mid    = $m['id'] ?? 0;
    $did    = $draw->id ?? 0;
@endphp
<div class="bk-card {{ $isBye ? 'bk-card--bye' : '' }} {{ $wId ? 'bk-card--resolved' : '' }}">
    @if($isAdmin && $wId)
    <button class="bk-reset-btn" onclick="tkbResetWinner({{ $did }}, {{ $mid }})" type="button" title="Annuler résultat">↺</button>
    @endif
    {{-- Athlete 1 --}}
    <div class="bk-row
        {{ (!$ph1 && $wId && $wId==($a1['id']??null)) ? 'bk-row--win' : '' }}
        {{ $ph1 ? 'bk-row--tbd' : '' }}
        {{ ($isAdmin && !$wId && !$ph1) ? 'bk-row--clickable' : '' }}"
        @if($isAdmin && !$wId && !$ph1) onclick="tkbSetWinner({{ $did }}, {{ $mid }}, {{ $a1['id']??0 }})" @endif>
        <span class="bk-seed {{ $ph1 ? 'bk-seed--tbd' : '' }}">{{ $s1 }}</span>
        <div style="min-width:0;flex:1">
            @if(!$ph1)
                <div class="bk-name">{{ $a1['name']??'?' }}</div>
                @if(!empty($a1['club']))<div class="bk-club">{{ $a1['club'] }}</div>@endif
            @else
                <div class="bk-name bk-name--tbd">{{ $a1['name']??'En attente' }}</div>
            @endif
        </div>
        @if($isAdmin && !$wId && !$ph1)<span class="bk-set-icon">▶</span>@endif
    </div>
    {{-- Athlete 2 / BYE --}}
    @if($isBye)
    <div class="bk-row bk-row--bye">
        <span class="bk-seed bk-seed--bye"><svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>
        <div class="bk-name bk-name--bye">Exempt</div>
    </div>
    @else
    <div class="bk-row
        {{ (!$ph2 && $wId && $wId==($a2['id']??null)) ? 'bk-row--win' : '' }}
        {{ $ph2 ? 'bk-row--tbd' : '' }}
        {{ ($isAdmin && !$wId && !$ph2) ? 'bk-row--clickable' : '' }}"
        @if($isAdmin && !$wId && !$ph2) onclick="tkbSetWinner({{ $did }}, {{ $mid }}, {{ $a2['id']??0 }})" @endif>
        <span class="bk-seed {{ $ph2 ? 'bk-seed--tbd' : '' }}">{{ $s2 }}</span>
        <div style="min-width:0;flex:1">
            @if(!$ph2)
                <div class="bk-name">{{ $a2['name']??'?' }}</div>
                @if(!empty($a2['club']))<div class="bk-club">{{ $a2['club'] }}</div>@endif
            @else
                <div class="bk-name bk-name--tbd">{{ $a2['name']??'En attente' }}</div>
            @endif
        </div>
        @if($isAdmin && !$wId && !$ph2)<span class="bk-set-icon">▶</span>@endif
    </div>
    @endif
</div>
