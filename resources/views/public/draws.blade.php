<x-public-layout :title="'Tirages — ' . $event->name" :description="'Tirages officiels de ' . $event->name">

<div style="background:#06060a;min-height:100vh;padding-top:80px;">

{{-- ═══════════════════════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════════════════════════ --}}
<div style="position:relative;overflow:hidden;background:#06060a;border-bottom:1px solid rgba(245,158,11,0.1);">
    {{-- Grid texture --}}
    <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(245,158,11,0.025) 1px,transparent 1px),linear-gradient(90deg,rgba(245,158,11,0.025) 1px,transparent 1px);background-size:56px 56px;pointer-events:none;"></div>
    {{-- Radial glow --}}
    <div style="position:absolute;top:-60px;left:50%;transform:translateX(-50%);width:700px;height:320px;background:radial-gradient(ellipse,rgba(245,158,11,0.07) 0%,transparent 65%);pointer-events:none;"></div>

    <div style="max-width:1280px;margin:0 auto;padding:5rem 2.5rem 4rem;position:relative;">
        <a href="{{ route('public.event-detail', $event->slug) }}"
           style="display:inline-flex;align-items:center;gap:8px;color:rgba(255,255,255,0.28);font-size:0.68rem;text-decoration:none;margin-bottom:3rem;text-transform:uppercase;letter-spacing:0.14em;font-family:'Space Grotesk',sans-serif;transition:color 0.2s;"
           onmouseover="this.style.color='rgba(245,158,11,0.8)'" onmouseout="this.style.color='rgba(255,255,255,0.28)'">
            <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            {{ $event->name }}
        </a>

        <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:2rem;flex-wrap:wrap;">
            <div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:1.1rem;">
                    <div style="width:28px;height:2px;background:#f59e0b;"></div>
                    <span style="font-size:0.58rem;font-weight:700;color:#f59e0b;letter-spacing:0.32em;text-transform:uppercase;font-family:'Space Grotesk',sans-serif;">Tirages officiels</span>
                    <div style="width:28px;height:2px;background:#f59e0b;"></div>
                </div>
                <h1 style="font-size:clamp(2rem,5vw,3.8rem);font-weight:900;color:#fff;line-height:1;letter-spacing:-0.04em;margin:0 0 1rem;font-family:'Space Grotesk',sans-serif;text-transform:uppercase;">
                    {{ $event->name }}
                </h1>
                <span style="display:inline-flex;align-items:center;gap:7px;background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.22);padding:5px 14px;">
                    <svg style="width:11px;height:11px;color:#f59e0b;" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span style="font-size:0.62rem;font-weight:700;color:#f59e0b;letter-spacing:0.1em;text-transform:uppercase;">{{ $draws->count() }} catégorie(s) tirée(s)</span>
                </span>
            </div>
            <a href="{{ route('public.athlete-list', $event->slug) }}"
               style="display:inline-flex;align-items:center;gap:8px;color:rgba(255,255,255,0.45);font-size:0.68rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;border:1px solid rgba(255,255,255,0.1);padding:11px 22px;transition:all 0.2s;flex-shrink:0;"
               onmouseover="this.style.color='#f59e0b';this.style.borderColor='rgba(245,158,11,0.45)'" onmouseout="this.style.color='rgba(255,255,255,0.45)';this.style.borderColor='rgba(255,255,255,0.1)'">
                Liste des athlètes
                <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- Empty state --}}
@if($draws->isEmpty())
<div style="padding:8rem 0;text-align:center;">
    <svg style="width:52px;height:52px;color:rgba(255,255,255,0.06);margin:0 auto 1.5rem;display:block;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18m-7 5h7"/></svg>
    <p style="color:rgba(255,255,255,0.2);font-size:0.875rem;letter-spacing:0.06em;">Les tirages au sort n'ont pas encore été effectués pour cet événement.</p>
</div>
@else

@php
    $roundLabels = [1=>'Finale', 2=>'Demi-finales', 3=>'Quarts de finale', 4=>'Huitièmes', 5=>'Seizièmes'];
@endphp

<div style="padding:5rem 0 8rem;">
<div style="max-width:1400px;margin:0 auto;padding:0 2rem;">

@php $drawNum = 0; @endphp
@foreach($draws as $draw)
@php
    $drawNum++;
    $genderLabel  = \App\Models\Athlete::genderLabel($draw->gender, $draw->age_category ?? '');
    $genderColor  = $draw->gender === 'M' ? '#60a5fa' : '#f472b6';
    $genderBg     = $draw->gender === 'M' ? 'rgba(96,165,250,0.07)' : 'rgba(244,114,182,0.07)';
    $genderBorder = $draw->gender === 'M' ? 'rgba(96,165,250,0.2)' : 'rgba(244,114,182,0.2)';

    $champion = null; $runnerUp = null;
    if (!$draw->use_pools && $draw->matches) {
        $final = collect($draw->matches)->where('round', 1)->first();
        if ($final && !empty($final['winner'])) {
            $champion = $final['winner'];
            $a1 = $final['athlete1'] ?? null; $a2 = $final['athlete2'] ?? null;
            if ($a1 && $a2 && !empty($final['winner_id'])) {
                $runnerUp = ($a1['id'] ?? null) == $final['winner_id'] ? $a2 : $a1;
            }
        }
    }
    if ($draw->use_pools && $draw->pools) {
        $gf = collect($draw->pools['finals'] ?? [])->where('pool','FINALE')->first();
        if ($gf && !empty($gf['winner'])) $champion = $gf['winner'];
    }
@endphp

{{-- ════════════════════════════════════════════════════════════════════════
     CATEGORY SECTION
════════════════════════════════════════════════════════════════════════════ --}}
<div style="margin-bottom:7rem;">

    {{-- Category header --}}
    <div style="display:flex;align-items:flex-start;gap:1.5rem;margin-bottom:2.5rem;">
        {{-- Large number watermark --}}
        <div style="font-family:'Space Grotesk',sans-serif;font-size:5.5rem;font-weight:900;color:rgba(245,158,11,0.05);line-height:1;flex-shrink:0;letter-spacing:-0.06em;user-select:none;margin-top:-8px;">{{ str_pad($drawNum,2,'0',STR_PAD_LEFT) }}</div>

        <div style="flex:1;min-width:0;padding-top:4px;">
            {{-- Gender pill --}}
            <div style="display:inline-flex;align-items:center;gap:6px;background:{{ $genderBg }};border:1px solid {{ $genderBorder }};padding:3px 11px;margin-bottom:9px;">
                <div style="width:5px;height:5px;border-radius:50%;background:{{ $genderColor }};"></div>
                <span style="font-size:0.56rem;font-weight:700;color:{{ $genderColor }};text-transform:uppercase;letter-spacing:0.2em;font-family:'Space Grotesk',sans-serif;">{{ $genderLabel }}</span>
            </div>
            <h2 style="font-size:clamp(1.5rem,3.5vw,2.4rem);font-weight:900;color:#fff;margin:0 0 8px;font-family:'Space Grotesk',sans-serif;letter-spacing:-0.035em;text-transform:uppercase;line-height:1.05;">
                {{ $draw->age_category }} <span style="color:rgba(255,255,255,0.28);font-weight:400;font-size:0.75em;">{{ $draw->weight_category }}</span>
            </h2>
            <div style="display:flex;align-items:center;gap:1.2rem;flex-wrap:wrap;">
                <span style="font-size:0.6rem;color:rgba(255,255,255,0.22);letter-spacing:0.1em;text-transform:uppercase;">{{ $draw->total_athletes }} athlète(s)</span>
                <span style="width:3px;height:3px;border-radius:50%;background:rgba(255,255,255,0.1);flex-shrink:0;"></span>
                <span style="font-size:0.6rem;color:rgba(255,255,255,0.22);letter-spacing:0.1em;text-transform:uppercase;">{{ $draw->use_pools ? 'Format poules' : 'Élimination directe' }}</span>
                @if($draw->generated_at)
                <span style="width:3px;height:3px;border-radius:50%;background:rgba(255,255,255,0.1);flex-shrink:0;"></span>
                <span style="font-size:0.6rem;color:rgba(255,255,255,0.15);letter-spacing:0.08em;">{{ $draw->generated_at->format('d/m/Y') }}</span>
                @endif
            </div>
        </div>

        {{-- Decorative line --}}
        <div style="flex:1;height:1px;background:linear-gradient(90deg,rgba(245,158,11,0.18),transparent);max-width:180px;margin-top:28px;"></div>
    </div>

    {{-- ────────────────────── CHAMPION BLOCK ────────────────────────────── --}}
    @if($champion)
    <div style="position:relative;overflow:hidden;margin-bottom:2.5rem;">
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 20% 50%,rgba(245,158,11,0.09) 0%,transparent 55%);pointer-events:none;"></div>
        <div style="position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(180deg,#f59e0b,rgba(245,158,11,0.4));"></div>
        <div style="border:1px solid rgba(245,158,11,0.28);border-left:none;background:rgba(245,158,11,0.03);padding:1.5rem 2rem 1.5rem 2rem;display:flex;align-items:center;gap:2rem;flex-wrap:wrap;">
            {{-- Trophy --}}
            <div style="width:52px;height:52px;background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:26px;height:26px;color:#f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                </svg>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:0.52rem;font-weight:700;color:rgba(245,158,11,0.55);text-transform:uppercase;letter-spacing:0.32em;margin-bottom:5px;font-family:'Space Grotesk',sans-serif;">Champion · {{ $draw->age_category }} {{ $genderLabel }} {{ $draw->weight_category }}</div>
                <div style="font-size:1.7rem;font-weight:900;color:#f59e0b;font-family:'Space Grotesk',sans-serif;letter-spacing:-0.02em;text-transform:uppercase;line-height:1.1;">{{ $champion['name'] ?? '' }}</div>
                @if(!empty($champion['club']))<div style="font-size:0.68rem;color:rgba(255,255,255,0.28);margin-top:4px;text-transform:uppercase;letter-spacing:0.08em;">{{ $champion['club'] }}</div>@endif
            </div>
            @if($runnerUp)
            <div style="padding-left:2rem;border-left:1px solid rgba(255,255,255,0.07);">
                <div style="font-size:0.52rem;font-weight:700;color:rgba(255,255,255,0.2);text-transform:uppercase;letter-spacing:0.3em;margin-bottom:5px;font-family:'Space Grotesk',sans-serif;">Finaliste</div>
                <div style="font-size:1.05rem;font-weight:700;color:rgba(255,255,255,0.42);font-family:'Space Grotesk',sans-serif;text-transform:uppercase;letter-spacing:-0.01em;">{{ $runnerUp['name'] ?? '' }}</div>
                @if(!empty($runnerUp['club']))<div style="font-size:0.62rem;color:rgba(255,255,255,0.18);margin-top:3px;text-transform:uppercase;">{{ $runnerUp['club'] }}</div>@endif
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════
         DIRECT ELIMINATION BRACKET
    ═════════════════════════════════════════════════════════════════════════ --}}
    @if(!$draw->use_pools && $draw->matches)
    @php
        $allMatches     = collect($draw->matches);
        $matchesByRound = $allMatches
            ->filter(fn($m) => ($m['athlete1'] !== null || $m['athlete2'] !== null))
            ->groupBy('round')
            ->sortKeysDesc();

        $maxRound  = $matchesByRound->keys()->max();
        $roundKeys = $matchesByRound->keys()->values()->toArray();

        $bCardW = 232;
        $bConnW = 48;
        $bSlotH = 116;
        $bHdrH  = 56;
        $lineC  = 'rgba(245,158,11,0.5)';
    @endphp

    <div style="overflow-x:auto;padding-bottom:2rem;-webkit-overflow-scrolling:touch;">
    <div style="display:inline-flex;align-items:flex-start;min-width:max-content;">

    @foreach($matchesByRound as $round => $roundMatches)
    @php
        $loopIdx    = array_search($round, $roundKeys);
        $isFirst    = $loopIdx === 0;
        $isLast     = $round === 1;
        $slotH      = (int) round($bSlotH * pow(2, $maxRound - $round));
        $matchesArr = $roundMatches->sortBy('position')->values();
        $roundLabel = $roundLabels[$round] ?? ($round === $maxRound ? 'Premier tour' : "Tour {$round}");
        $colW       = $bCardW + ($isFirst ? 0 : $bConnW) + ($isLast ? 0 : $bConnW);
    @endphp

    <div style="display:flex;flex-direction:column;flex-shrink:0;">

        {{-- Round column header --}}
        <div style="width:{{ $colW }}px;height:{{ $bHdrH }}px;display:flex;align-items:center;justify-content:center;">
            <div style="
                padding:6px 18px;
                background:{{ $isLast ? 'rgba(245,158,11,0.1)' : 'rgba(255,255,255,0.03)' }};
                border:1px solid {{ $isLast ? 'rgba(245,158,11,0.38)' : 'rgba(255,255,255,0.07)' }};
                display:flex;align-items:center;gap:7px;
            ">
                @if($isLast)
                <svg style="width:10px;height:10px;color:#f59e0b;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endif
                <span style="font-size:0.57rem;font-weight:800;color:{{ $isLast ? '#f59e0b' : 'rgba(255,255,255,0.28)' }};text-transform:uppercase;letter-spacing:0.24em;font-family:'Space Grotesk',sans-serif;">{{ $roundLabel }}</span>
            </div>
        </div>

        {{-- Match slots --}}
        @foreach($matchesArr as $match)
        @php
            $a1  = $match['athlete1'] ?? null;
            $a2  = $match['athlete2'] ?? null;
            $wid = $match['winner_id'] ?? null;
            $a1w = $wid && $a1 && isset($a1['id']) && (int)$a1['id'] === (int)$wid;
            $a2w = $wid && $a2 && isset($a2['id']) && (int)$a2['id'] === (int)$wid;
            $ph1 = !empty($a1['placeholder']);
            $ph2 = !empty($a2['placeholder']);
            $pos = (int)($match['position'] ?? 1);
            $isTopOfPair = ($pos % 2 !== 0);
            $isBye    = ($match['is_bye'] ?? false);
            $hasWinner = (bool)$wid;
        @endphp

        <div style="height:{{ $slotH }}px;width:{{ $colW }}px;position:relative;display:flex;align-items:center;">

            {{-- Left connector arm --}}
            @if(!$isFirst)
            <div style="width:{{ $bConnW }}px;height:2px;background:{{ $lineC }};flex-shrink:0;"></div>
            @endif

            {{-- ─────────── MATCH CARD ─────────── --}}
            <div style="
                width:{{ $bCardW }}px;flex-shrink:0;position:relative;overflow:hidden;
                background:{{ $isLast ? '#0e0e16' : '#0b0b0f' }};
                border:1px solid {{ $isLast ? 'rgba(245,158,11,0.38)' : ($hasWinner ? 'rgba(255,255,255,0.1)' : 'rgba(255,255,255,0.07)') }};
                box-shadow:{{ $isLast ? '0 0 40px rgba(245,158,11,0.07),0 4px 20px rgba(0,0,0,0.7)' : '0 2px 10px rgba(0,0,0,0.5)' }};
            ">
                {{-- Left accent stripe (gender color, gold for final) --}}
                <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:{{ $isLast ? '#f59e0b' : $genderColor }};opacity:{{ $isLast ? '1' : '0.55' }};"></div>

                {{-- Top strip --}}
                <div style="margin-left:3px;padding:4px 10px;border-bottom:1px solid rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,0.015);">
                    <span style="font-size:0.44rem;font-weight:700;color:rgba(255,255,255,0.16);text-transform:uppercase;letter-spacing:0.18em;font-family:'Space Grotesk',sans-serif;">Combat {{ $match['id'] ?? '' }}</span>
                    @if($hasWinner && $isLast)
                    <span style="font-size:0.42rem;font-weight:700;color:rgba(245,158,11,0.7);background:rgba(245,158,11,0.1);padding:1px 6px;text-transform:uppercase;letter-spacing:0.1em;">Terminé</span>
                    @elseif($isBye && !$hasWinner)
                    <span style="font-size:0.42rem;font-weight:700;color:rgba(255,255,255,0.18);background:rgba(255,255,255,0.04);padding:1px 6px;text-transform:uppercase;letter-spacing:0.1em;">Bye</span>
                    @endif
                </div>

                {{-- Athlete 1 --}}
                <div style="margin-left:3px;padding:10px 12px;border-bottom:1px solid rgba(255,255,255,0.04);display:flex;align-items:center;gap:9px;min-height:47px;background:{{ $a1w ? 'rgba(245,158,11,0.07)' : 'transparent' }};">
                    <div style="width:6px;height:6px;border-radius:50%;flex-shrink:0;background:{{ $a1w ? '#f59e0b' : ($hasWinner ? 'rgba(255,255,255,0.05)' : 'rgba(255,255,255,0.1)') }};"></div>
                    <div style="flex:1;min-width:0;">
                        @if($a1 && !$ph1)
                        <div style="font-size:0.79rem;font-weight:{{ $a1w ? '800' : '500' }};color:{{ $a1w ? '#f59e0b' : ($hasWinner ? 'rgba(255,255,255,0.22)' : 'rgba(255,255,255,0.88)') }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-family:'Space Grotesk',sans-serif;text-transform:uppercase;letter-spacing:0.01em;">{{ $a1['name'] ?? '' }}</div>
                        @if(!empty($a1['club']))<div style="font-size:0.54rem;color:rgba(255,255,255,0.16);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;letter-spacing:0.07em;">{{ $a1['club'] }}</div>@endif
                        @elseif($a1 && $ph1)
                        <div style="font-size:0.72rem;color:rgba(255,255,255,0.18);font-style:italic;">{{ $a1['name'] }}</div>
                        @else
                        <div style="font-size:0.66rem;color:rgba(255,255,255,0.12);letter-spacing:0.12em;font-family:'Space Grotesk',sans-serif;">— BYE —</div>
                        @endif
                    </div>
                    @if($a1w)<svg style="width:13px;height:13px;color:#f59e0b;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>@endif
                </div>

                {{-- Athlete 2 --}}
                <div style="margin-left:3px;padding:10px 12px;display:flex;align-items:center;gap:9px;min-height:47px;background:{{ $a2w ? 'rgba(245,158,11,0.07)' : 'transparent' }};">
                    <div style="width:6px;height:6px;border-radius:50%;flex-shrink:0;background:{{ $a2w ? '#f59e0b' : ($hasWinner ? 'rgba(255,255,255,0.05)' : 'rgba(255,255,255,0.1)') }};"></div>
                    <div style="flex:1;min-width:0;">
                        @if($a2 && !$ph2)
                        <div style="font-size:0.79rem;font-weight:{{ $a2w ? '800' : '500' }};color:{{ $a2w ? '#f59e0b' : ($hasWinner ? 'rgba(255,255,255,0.22)' : 'rgba(255,255,255,0.88)') }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-family:'Space Grotesk',sans-serif;text-transform:uppercase;letter-spacing:0.01em;">{{ $a2['name'] ?? '' }}</div>
                        @if(!empty($a2['club']))<div style="font-size:0.54rem;color:rgba(255,255,255,0.16);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;letter-spacing:0.07em;">{{ $a2['club'] }}</div>@endif
                        @elseif($a2 && $ph2)
                        <div style="font-size:0.72rem;color:rgba(255,255,255,0.18);font-style:italic;">{{ $a2['name'] }}</div>
                        @else
                        <div style="font-size:0.66rem;color:rgba(255,255,255,0.12);letter-spacing:0.12em;font-family:'Space Grotesk',sans-serif;">— BYE —</div>
                        @endif
                    </div>
                    @if($a2w)<svg style="width:13px;height:13px;color:#f59e0b;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>@endif
                </div>
            </div>
            {{-- end match card --}}

            {{-- Right bracket arm --}}
            @if(!$isLast)
            <div style="
                position:absolute;right:0;width:{{ $bConnW }}px;
                {{ $isTopOfPair
                    ? "top:50%;height:50%;border-top:2px solid {$lineC};border-right:2px solid {$lineC};"
                    : "top:0;height:50%;border-bottom:2px solid {$lineC};border-right:2px solid {$lineC};"
                }}
            "></div>
            @endif

        </div>
        @endforeach {{-- matches --}}

    </div>
    @endforeach {{-- rounds --}}

    </div>
    </div>
    @endif
    {{-- end direct elimination --}}

    {{-- ════════════════════════════════════════════════════════════════════
         POOL FORMAT
    ═════════════════════════════════════════════════════════════════════════ --}}
    @if($draw->use_pools && $draw->pools)
    @php
        $pools  = $draw->pools['pools']  ?? [];
        $finals = $draw->pools['finals'] ?? [];
    @endphp

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;margin-bottom:3rem;">
        @foreach($pools as $pool)
        @php
            $poolWins = [];
            foreach ($pool['athletes'] as $a) { $poolWins[$a['id']] = 0; }
            foreach ($pool['matches'] as $m) {
                if (!empty($m['winner_id'])) $poolWins[$m['winner_id']] = ($poolWins[$m['winner_id']] ?? 0) + 1;
            }
            arsort($poolWins);
        @endphp
        <div style="background:#0b0b0f;border:1px solid rgba(255,255,255,0.07);overflow:hidden;position:relative;">
            <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:{{ $genderColor }};opacity:0.55;"></div>
            <div style="padding:11px 14px 11px 17px;border-bottom:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:0.58rem;font-weight:700;color:#f59e0b;text-transform:uppercase;letter-spacing:0.22em;font-family:'Space Grotesk',sans-serif;">{{ $pool['name'] }}</span>
                <span style="font-size:0.54rem;color:rgba(255,255,255,0.18);text-transform:uppercase;letter-spacing:0.1em;">{{ count($pool['athletes']) }} combattants</span>
            </div>
            {{-- Standings --}}
            <div>
                @foreach(array_keys($poolWins) as $rank => $athleteId)
                @php
                    $athlete = collect($pool['athletes'])->firstWhere('id', $athleteId);
                    $wins    = $poolWins[$athleteId];
                    $r1      = $rank === 0;
                @endphp
                @if($athlete)
                <div style="padding:9px 14px 9px 17px;display:flex;align-items:center;gap:10px;border-bottom:1px solid rgba(255,255,255,0.04);background:{{ $r1 && $wins > 0 ? 'rgba(245,158,11,0.04)' : 'transparent' }};">
                    <div style="width:18px;text-align:center;font-size:0.62rem;font-weight:800;color:{{ $r1 && $wins > 0 ? '#f59e0b' : 'rgba(255,255,255,0.14)' }};font-family:'Space Grotesk',sans-serif;flex-shrink:0;">{{ $rank + 1 }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:0.77rem;font-weight:{{ $r1 && $wins > 0 ? '800' : '500' }};color:{{ $r1 && $wins > 0 ? '#fff' : 'rgba(255,255,255,0.58)' }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;font-family:'Space Grotesk',sans-serif;letter-spacing:0.01em;">{{ $athlete['name'] }}</div>
                        @if(!empty($athlete['club']))<div style="font-size:0.54rem;color:rgba(255,255,255,0.2);margin-top:1px;text-transform:uppercase;letter-spacing:0.06em;">{{ $athlete['club'] }}</div>@endif
                    </div>
                    <div style="font-size:0.68rem;font-weight:800;color:{{ $wins > 0 ? '#f59e0b' : 'rgba(255,255,255,0.14)' }};flex-shrink:0;font-family:'Space Grotesk',sans-serif;">{{ $wins }}V</div>
                </div>
                @endif
                @endforeach
            </div>
            {{-- Pool matches --}}
            <div style="padding:10px 14px 10px 17px;border-top:1px solid rgba(255,255,255,0.04);">
                <div style="font-size:0.5rem;font-weight:700;color:rgba(255,255,255,0.14);text-transform:uppercase;letter-spacing:0.2em;margin-bottom:8px;font-family:'Space Grotesk',sans-serif;">Combats</div>
                @foreach($pool['matches'] as $mi => $match)
                @php $a1=$match['athlete1']??null; $a2=$match['athlete2']??null; $wid=$match['winner_id']??null; @endphp
                <div style="display:flex;align-items:center;gap:8px;padding:5px 0;{{ $mi < count($pool['matches'])-1 ? 'border-bottom:1px solid rgba(255,255,255,0.03);' : '' }}">
                    <span style="font-size:0.5rem;color:rgba(255,255,255,0.14);width:18px;flex-shrink:0;font-family:'Space Grotesk',sans-serif;font-weight:700;">C{{ $mi+1 }}</span>
                    <span style="font-size:0.74rem;font-weight:{{ $wid && $a1 && ($a1['id']??null)==$wid ? '700' : '400' }};color:{{ $wid && $a1 && ($a1['id']??null)==$wid ? '#f59e0b' : 'rgba(255,255,255,0.48)' }};flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;">{{ $a1['name']??'—' }}</span>
                    <span style="font-size:0.48rem;font-weight:800;color:rgba(255,255,255,0.14);flex-shrink:0;letter-spacing:0.12em;">VS</span>
                    <span style="font-size:0.74rem;font-weight:{{ $wid && $a2 && ($a2['id']??null)==$wid ? '700' : '400' }};color:{{ $wid && $a2 && ($a2['id']??null)==$wid ? '#f59e0b' : 'rgba(255,255,255,0.48)' }};flex:1;text-align:right;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;">{{ $a2['name']??'—' }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- Finals phase --}}
    @if(count($finals))
    <div style="border-top:1px solid rgba(245,158,11,0.1);padding-top:2.5rem;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:2rem;">
            <div style="width:22px;height:2px;background:#f59e0b;"></div>
            <span style="font-size:0.56rem;font-weight:700;color:rgba(245,158,11,0.65);text-transform:uppercase;letter-spacing:0.3em;font-family:'Space Grotesk',sans-serif;">Phase finale</span>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:1rem;">
            @php $finalsByPhase = collect($finals)->groupBy('pool'); @endphp
            @foreach($finalsByPhase as $phase => $phaseMatches)
            <div style="flex:1;min-width:240px;max-width:320px;">
                <div style="font-size:0.54rem;font-weight:700;color:{{ $phase==='FINALE' ? '#f59e0b' : 'rgba(255,255,255,0.28)' }};text-transform:uppercase;letter-spacing:0.2em;margin-bottom:0.75rem;padding-bottom:0.5rem;border-bottom:1px solid rgba(245,158,11,{{ $phase==='FINALE' ? '0.25' : '0.06' }});font-family:'Space Grotesk',sans-serif;">{{ $phase }}</div>
                @foreach($phaseMatches as $match)
                @php
                    $a1=$match['athlete1']??null; $a2=$match['athlete2']??null; $wid=$match['winner_id']??null;
                    $ph1=!empty($a1['placeholder']); $ph2=!empty($a2['placeholder']);
                    $a1w=$wid&&$a1&&!$ph1&&($a1['id']??null)==$wid;
                    $a2w=$wid&&$a2&&!$ph2&&($a2['id']??null)==$wid;
                @endphp
                <div style="background:#0b0b0f;border:1px solid rgba(245,158,11,{{ $phase==='FINALE' ? '0.25' : '0.07' }});overflow:hidden;margin-bottom:0.5rem;position:relative;">
                    <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:{{ $phase==='FINALE' ? '#f59e0b' : 'rgba(255,255,255,0.1)' }};"></div>
                    <div style="margin-left:3px;padding:9px 12px;border-bottom:1px solid rgba(255,255,255,0.04);display:flex;align-items:center;gap:8px;background:{{ $a1w ? 'rgba(245,158,11,0.07)' : 'transparent' }};">
                        @if($a1w)<svg style="width:10px;height:10px;color:#f59e0b;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>@else<div style="width:10px;flex-shrink:0;"></div>@endif
                        <span style="font-size:0.77rem;font-weight:{{ $a1w ? '800' : '400' }};color:{{ $a1w ? '#f59e0b' : ($ph1 ? 'rgba(255,255,255,0.18)' : 'rgba(255,255,255,0.68)') }};text-transform:uppercase;font-family:'Space Grotesk',sans-serif;letter-spacing:0.01em;">{{ $a1['name']??'—' }}</span>
                    </div>
                    <div style="margin-left:3px;padding:9px 12px;display:flex;align-items:center;gap:8px;background:{{ $a2w ? 'rgba(245,158,11,0.07)' : 'transparent' }};">
                        @if($a2w)<svg style="width:10px;height:10px;color:#f59e0b;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>@else<div style="width:10px;flex-shrink:0;"></div>@endif
                        <span style="font-size:0.77rem;font-weight:{{ $a2w ? '800' : '400' }};color:{{ $a2w ? '#f59e0b' : ($ph2 ? 'rgba(255,255,255,0.18)' : 'rgba(255,255,255,0.68)') }};text-transform:uppercase;font-family:'Space Grotesk',sans-serif;letter-spacing:0.01em;">{{ $a2['name']??'—' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @endif
    {{-- end pools --}}

</div>
{{-- end category section --}}

@endforeach

</div>
</div>
@endif

</div>

</x-public-layout>
