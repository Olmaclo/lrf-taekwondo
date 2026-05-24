<x-public-layout :title="'Tirages — ' . $event->name" :description="'Tirages officiels de ' . $event->name">

<div style="background:#06060a;min-height:100vh;padding-top:80px;">

{{-- HERO --}}
<div style="position:relative;overflow:hidden;border-bottom:1px solid rgba(245,158,11,0.1);">
    <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(245,158,11,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(245,158,11,0.02) 1px,transparent 1px);background-size:60px 60px;pointer-events:none;"></div>
    <div style="position:absolute;top:-80px;left:50%;transform:translateX(-50%);width:700px;height:340px;background:radial-gradient(ellipse,rgba(245,158,11,0.06) 0%,transparent 65%);pointer-events:none;"></div>
    <div style="max-width:1280px;margin:0 auto;padding:5rem 2.5rem 4rem;position:relative;">
        <a href="{{ route('public.event-detail', $event->slug) }}"
           style="display:inline-flex;align-items:center;gap:8px;color:rgba(255,255,255,0.25);font-size:0.68rem;text-decoration:none;margin-bottom:3rem;text-transform:uppercase;letter-spacing:0.14em;font-family:'Space Grotesk',sans-serif;"
           onmouseover="this.style.color='rgba(245,158,11,0.8)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">
            <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            {{ $event->name }}
        </a>
        <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:2rem;flex-wrap:wrap;">
            <div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:1rem;">
                    <div style="width:28px;height:2px;background:#f59e0b;"></div>
                    <span style="font-size:0.58rem;font-weight:700;color:#f59e0b;letter-spacing:0.32em;text-transform:uppercase;font-family:'Space Grotesk',sans-serif;">Tirages officiels</span>
                    <div style="width:28px;height:2px;background:#f59e0b;"></div>
                </div>
                <h1 style="font-size:clamp(2rem,5vw,3.8rem);font-weight:900;color:#fff;line-height:1;letter-spacing:-0.04em;margin:0 0 1rem;font-family:'Space Grotesk',sans-serif;text-transform:uppercase;">{{ $event->name }}</h1>
                <span style="display:inline-flex;align-items:center;gap:7px;background:rgba(245,158,11,0.07);border:1px solid rgba(245,158,11,0.2);padding:5px 14px;">
                    <span style="font-size:0.62rem;font-weight:700;color:#f59e0b;letter-spacing:0.1em;text-transform:uppercase;">{{ $draws->count() }} catégorie(s)</span>
                </span>
            </div>
            <a href="{{ route('public.athlete-list', $event->slug) }}"
               style="display:inline-flex;align-items:center;gap:8px;color:rgba(255,255,255,0.4);font-size:0.68rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;border:1px solid rgba(255,255,255,0.1);padding:11px 22px;"
               onmouseover="this.style.color='#f59e0b';this.style.borderColor='rgba(245,158,11,0.4)'" onmouseout="this.style.color='rgba(255,255,255,0.4)';this.style.borderColor='rgba(255,255,255,0.1)'">
                Liste des athlètes <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</div>

@if($draws->isEmpty())
<div style="padding:8rem 0;text-align:center;">
    <p style="color:rgba(255,255,255,0.2);font-size:0.875rem;">Les tirages n'ont pas encore été effectués.</p>
</div>
@else

@php $roundLabels=[1=>'Finale',2=>'Demi-finales',3=>'Quarts',4=>'Huitièmes',5=>'1er Tour']; @endphp

<div style="padding:5rem 0 8rem;">
<div style="max-width:1400px;margin:0 auto;padding:0 2rem;">

@php $drawNum=0; @endphp
@foreach($draws as $draw)
@php
    $drawNum++;
    $genderLabel = \App\Models\Athlete::genderLabel($draw->gender,$draw->age_category??'');
    $genderColor = $draw->gender==='M' ? '#60a5fa' : '#f472b6';
    $champion=null; $runnerUp=null;
    if(!$draw->use_pools && $draw->matches){
        $final=collect($draw->matches)->where('round',1)->first();
        if($final && !empty($final['winner'])){
            $champion=$final['winner'];
            $a1f=$final['athlete1']??null; $a2f=$final['athlete2']??null;
            if($a1f && $a2f && !empty($final['winner_id']))
                $runnerUp=($a1f['id']??null)==$final['winner_id'] ? $a2f : $a1f;
        }
    }
    if($draw->use_pools && $draw->pools){
        $gf=collect($draw->pools['finals']??[])->where('pool','FINALE')->first();
        if($gf && !empty($gf['winner'])) $champion=$gf['winner'];
    }
@endphp

<div style="margin-bottom:8rem;">

    {{-- Category header --}}
    <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:3rem;padding-bottom:1.5rem;border-bottom:1px solid rgba(255,255,255,0.06);">
        <div style="font-family:'Space Grotesk',sans-serif;font-size:4rem;font-weight:900;color:rgba(245,158,11,0.06);line-height:1;flex-shrink:0;letter-spacing:-0.05em;user-select:none;">{{ str_pad($drawNum,2,'0',STR_PAD_LEFT) }}</div>
        <div>
            <div style="display:inline-flex;align-items:center;gap:6px;background:{{ $draw->gender==='M' ? 'rgba(96,165,250,0.07)' : 'rgba(244,114,182,0.07)' }};border:1px solid {{ $draw->gender==='M' ? 'rgba(96,165,250,0.2)' : 'rgba(244,114,182,0.2)' }};padding:3px 10px;margin-bottom:8px;">
                <div style="width:5px;height:5px;border-radius:50%;background:{{ $genderColor }};"></div>
                <span style="font-size:0.56rem;font-weight:700;color:{{ $genderColor }};text-transform:uppercase;letter-spacing:0.2em;font-family:'Space Grotesk',sans-serif;">{{ $genderLabel }}</span>
            </div>
            <h2 style="font-size:clamp(1.4rem,3vw,2.2rem);font-weight:900;color:#fff;margin:0 0 6px;font-family:'Space Grotesk',sans-serif;letter-spacing:-0.03em;text-transform:uppercase;line-height:1.05;">
                {{ $draw->age_category }} <span style="color:rgba(255,255,255,0.3);font-weight:400;font-size:0.72em;">{{ $draw->weight_category }}</span>
            </h2>
            <p style="font-size:0.6rem;color:rgba(255,255,255,0.22);letter-spacing:0.1em;text-transform:uppercase;margin:0;">
                {{ $draw->total_athletes }} athlète(s) &nbsp;·&nbsp; {{ $draw->use_pools ? 'Format poules' : 'Élimination directe' }}
                @if($draw->generated_at) &nbsp;·&nbsp; {{ $draw->generated_at->format('d/m/Y') }} @endif
            </p>
        </div>
    </div>

    {{-- Champion banner --}}
    @if($champion)
    <div style="position:relative;overflow:hidden;margin-bottom:2.5rem;">
        <div style="position:absolute;left:0;top:0;bottom:0;width:4px;background:#f59e0b;"></div>
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 15% 50%,rgba(245,158,11,0.08) 0%,transparent 50%);pointer-events:none;"></div>
        <div style="border:1px solid rgba(245,158,11,0.25);border-left:none;background:rgba(245,158,11,0.03);padding:1.5rem 2rem 1.5rem 1.75rem;display:flex;align-items:center;gap:2rem;flex-wrap:wrap;">
            <div style="width:48px;height:48px;background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:24px;height:24px;color:#f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:0.52rem;font-weight:700;color:rgba(245,158,11,0.5);text-transform:uppercase;letter-spacing:0.32em;margin-bottom:5px;font-family:'Space Grotesk',sans-serif;">Champion</div>
                <div style="font-size:1.7rem;font-weight:900;color:#f59e0b;font-family:'Space Grotesk',sans-serif;letter-spacing:-0.02em;text-transform:uppercase;line-height:1.1;">{{ $champion['name']??'' }}</div>
                @if(!empty($champion['club']))<div style="font-size:0.68rem;color:rgba(255,255,255,0.28);margin-top:4px;text-transform:uppercase;">{{ $champion['club'] }}</div>@endif
            </div>
            @if($runnerUp)
            <div style="padding-left:2rem;border-left:1px solid rgba(255,255,255,0.06);">
                <div style="font-size:0.52rem;font-weight:700;color:rgba(255,255,255,0.2);text-transform:uppercase;letter-spacing:0.28em;margin-bottom:5px;font-family:'Space Grotesk',sans-serif;">Finaliste</div>
                <div style="font-size:1rem;font-weight:700;color:rgba(255,255,255,0.4);font-family:'Space Grotesk',sans-serif;text-transform:uppercase;">{{ $runnerUp['name']??'' }}</div>
                @if(!empty($runnerUp['club']))<div style="font-size:0.62rem;color:rgba(255,255,255,0.18);margin-top:3px;text-transform:uppercase;">{{ $runnerUp['club'] }}</div>@endif
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ══ DIRECT ELIMINATION — BRACKET ══ --}}
    @if(!$draw->use_pools && $draw->matches)
    @php
        $allMatches     = collect($draw->matches);
        $matchesByRound = $allMatches
            ->filter(fn($m) => $m['athlete1']!==null || $m['athlete2']!==null)
            ->groupBy('round')
            ->sortKeysDesc();

        $maxRound  = $matchesByRound->keys()->max();
        $roundKeys = $matchesByRound->keys()->values()->toArray();

        // Slot dimensions
        $slotH  = 56;   // height of each athlete slot (px)
        $divH   = 2;    // divider between the two slots
        $cardH  = $slotH * 2 + $divH; // total match card height = 114px
        $cardW  = 240;  // match card width (px)
        $connW  = 48;   // connector arm width (px)
        $hdrH   = 52;   // round header height (px)
        $lc     = 'rgba(245,158,11,0.65)';  // line color
        $lw     = '3';                       // line width px
    @endphp

    <div style="overflow-x:auto;padding-bottom:1.5rem;-webkit-overflow-scrolling:touch;">
    <div style="display:inline-flex;align-items:flex-start;gap:0;min-width:max-content;">

    @foreach($matchesByRound as $round => $roundMatches)
    @php
        $loopIdx    = array_search($round, $roundKeys);
        $isFirst    = ($loopIdx === 0);
        $isLast     = ($round === 1);
        $containerH = (int) round(($slotH * 2 + $divH + 2) * pow(2, $maxRound - $round));
        $matchesArr = $roundMatches->sortBy('position')->values();
        $roundLabel = $roundLabels[$round] ?? ($round===$maxRound ? '1er Tour' : "Tour {$round}");
        $colW       = $cardW + ($isFirst ? 0 : $connW) + ($isLast ? 0 : $connW);
    @endphp

    <div style="display:flex;flex-direction:column;flex-shrink:0;">

        {{-- Round header --}}
        <div style="width:{{ $colW }}px;height:{{ $hdrH }}px;display:flex;align-items:center;justify-content:center;padding:0 {{ $isFirst ? 0 : $connW }}px 0 {{ $isLast ? 0 : $connW }}px;">
            <div style="
                padding:6px 18px;
                background:{{ $isLast ? 'rgba(245,158,11,0.12)' : 'rgba(255,255,255,0.04)' }};
                border-top:{{ $isLast ? "3px solid #f59e0b" : "2px solid rgba(255,255,255,0.1)" }};
                border-left:1px solid {{ $isLast ? 'rgba(245,158,11,0.3)' : 'rgba(255,255,255,0.08)' }};
                border-right:1px solid {{ $isLast ? 'rgba(245,158,11,0.3)' : 'rgba(255,255,255,0.08)' }};
                border-bottom:1px solid {{ $isLast ? 'rgba(245,158,11,0.3)' : 'rgba(255,255,255,0.08)' }};
                display:flex;align-items:center;gap:7px;
            ">
                @if($isLast)<svg style="width:11px;height:11px;color:#f59e0b;" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endif
                <span style="font-size:0.6rem;font-weight:800;color:{{ $isLast ? '#f59e0b' : 'rgba(255,255,255,0.4)' }};text-transform:uppercase;letter-spacing:0.22em;font-family:'Space Grotesk',sans-serif;">{{ $roundLabel }}</span>
            </div>
        </div>

        {{-- Matches --}}
        @foreach($matchesArr as $mIdx => $match)
        @php
            $a1  = $match['athlete1'] ?? null;
            $a2  = $match['athlete2'] ?? null;
            $wid = $match['winner_id'] ?? null;
            $a1w = $wid && $a1 && isset($a1['id']) && (int)$a1['id']===(int)$wid;
            $a2w = $wid && $a2 && isset($a2['id']) && (int)$a2['id']===(int)$wid;
            $ph1 = !empty($a1['placeholder']);
            $ph2 = !empty($a2['placeholder']);
            $pos = (int)($match['position'] ?? 1);
            $isTopOfPair = ($pos % 2 !== 0);
            $hasWinner   = (bool)$wid;
            $seed1 = $a1['seed'] ?? null;
            $seed2 = $a2['seed'] ?? null;
        @endphp

        <div style="height:{{ $containerH }}px;width:{{ $colW }}px;position:relative;display:flex;align-items:center;">

            {{-- Left connector: horizontal line from previous round --}}
            @if(!$isFirst)
            <div style="width:{{ $connW }}px;height:{{ $lw }}px;background:{{ $lc }};flex-shrink:0;"></div>
            @endif

            {{-- MATCH CARD --}}
            <div style="
                width:{{ $cardW }}px;flex-shrink:0;
                border:{{ $isLast ? "2px solid rgba(245,158,11,0.5)" : "1px solid rgba(255,255,255,0.13)" }};
                box-shadow:{{ $isLast ? '0 0 40px rgba(245,158,11,0.10)' : 'none' }};
                overflow:hidden;
            ">

                {{-- Athlete 1 slot --}}
                @php
                    $bg1 = $a1w ? '#1c1200' : ($hasWinner && !$a1w ? '#080810' : '#0e0e18');
                    $tc1 = $a1w ? '#f59e0b' : ($hasWinner && !$a1w ? 'rgba(255,255,255,0.22)' : ($ph1 ? 'rgba(255,255,255,0.25)' : 'rgba(255,255,255,0.88)'));
                    $fw1 = $a1w ? '800' : ($hasWinner && !$a1w ? '400' : '600');
                    $ac1 = $a1w ? '#f59e0b' : ($hasWinner ? 'rgba(255,255,255,0.1)' : $genderColor);
                @endphp
                <div style="height:{{ $slotH }}px;display:flex;align-items:center;background:{{ $bg1 }};border-bottom:{{ $divH }}px solid #06060a;position:relative;">
                    <div style="width:6px;height:100%;flex-shrink:0;background:{{ $ac1 }};{{ $a1w ? '' : ($hasWinner ? 'opacity:0.35;' : 'opacity:0.7;') }}"></div>
                    @if($seed1 !== null && !$ph1 && $a1)
                    <div style="width:26px;text-align:center;flex-shrink:0;font-size:0.6rem;font-weight:800;color:{{ $a1w ? '#f59e0b' : 'rgba(255,255,255,0.2)' }};font-family:'Space Grotesk',sans-serif;">{{ $seed1 }}</div>
                    @endif
                    <div style="flex:1;min-width:0;padding:0 10px 0 {{ $seed1 !== null && !$ph1 && $a1 ? '0' : '10' }}px;">
                        @if($a1 && !$ph1)
                            <div style="font-size:0.82rem;font-weight:{{ $fw1 }};color:{{ $tc1 }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-family:'Space Grotesk',sans-serif;text-transform:uppercase;letter-spacing:0.02em;">{{ $a1['name']??'' }}</div>
                            @if(!empty($a1['club']) && !$hasWinner)<div style="font-size:0.53rem;color:rgba(255,255,255,0.2);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;letter-spacing:0.06em;">{{ $a1['club'] }}</div>@endif
                        @elseif($a1 && $ph1)
                            <div style="font-size:0.72rem;color:rgba(255,255,255,0.2);font-style:italic;font-family:'Space Grotesk',sans-serif;">{{ $a1['name'] }}</div>
                        @else
                            <div style="font-size:0.6rem;color:rgba(255,255,255,0.1);letter-spacing:0.14em;font-family:'Space Grotesk',sans-serif;">BYE</div>
                        @endif
                    </div>
                    @if($a1w)
                    <svg style="width:14px;height:14px;color:#f59e0b;flex-shrink:0;margin-right:10px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    @endif
                </div>

                {{-- Athlete 2 slot --}}
                @php
                    $bg2 = $a2w ? '#1c1200' : ($hasWinner && !$a2w ? '#080810' : '#0e0e18');
                    $tc2 = $a2w ? '#f59e0b' : ($hasWinner && !$a2w ? 'rgba(255,255,255,0.22)' : ($ph2 ? 'rgba(255,255,255,0.25)' : 'rgba(255,255,255,0.88)'));
                    $fw2 = $a2w ? '800' : ($hasWinner && !$a2w ? '400' : '600');
                    $ac2 = $a2w ? '#f59e0b' : ($hasWinner ? 'rgba(255,255,255,0.1)' : $genderColor);
                @endphp
                <div style="height:{{ $slotH }}px;display:flex;align-items:center;background:{{ $bg2 }};position:relative;">
                    <div style="width:6px;height:100%;flex-shrink:0;background:{{ $ac2 }};{{ $a2w ? '' : ($hasWinner ? 'opacity:0.35;' : 'opacity:0.7;') }}"></div>
                    @if($seed2 !== null && !$ph2 && $a2)
                    <div style="width:26px;text-align:center;flex-shrink:0;font-size:0.6rem;font-weight:800;color:{{ $a2w ? '#f59e0b' : 'rgba(255,255,255,0.2)' }};font-family:'Space Grotesk',sans-serif;">{{ $seed2 }}</div>
                    @endif
                    <div style="flex:1;min-width:0;padding:0 10px 0 {{ $seed2 !== null && !$ph2 && $a2 ? '0' : '10' }}px;">
                        @if($a2 && !$ph2)
                            <div style="font-size:0.82rem;font-weight:{{ $fw2 }};color:{{ $tc2 }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-family:'Space Grotesk',sans-serif;text-transform:uppercase;letter-spacing:0.02em;">{{ $a2['name']??'' }}</div>
                            @if(!empty($a2['club']) && !$hasWinner)<div style="font-size:0.53rem;color:rgba(255,255,255,0.2);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;letter-spacing:0.06em;">{{ $a2['club'] }}</div>@endif
                        @elseif($a2 && $ph2)
                            <div style="font-size:0.72rem;color:rgba(255,255,255,0.2);font-style:italic;font-family:'Space Grotesk',sans-serif;">{{ $a2['name'] }}</div>
                        @else
                            <div style="font-size:0.6rem;color:rgba(255,255,255,0.1);letter-spacing:0.14em;font-family:'Space Grotesk',sans-serif;">BYE</div>
                        @endif
                    </div>
                    @if($a2w)
                    <svg style="width:14px;height:14px;color:#f59e0b;flex-shrink:0;margin-right:10px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    @endif
                </div>

            </div>
            {{-- end match card --}}

            {{-- Right L-shaped bracket arm --}}
            @if(!$isLast)
            <div style="
                position:absolute;right:0;width:{{ $connW }}px;
                {{ $isTopOfPair
                    ? "top:50%;height:50%;border-top:{$lw}px solid {$lc};border-right:{$lw}px solid {$lc};"
                    : "top:0;height:50%;border-bottom:{$lw}px solid {$lc};border-right:{$lw}px solid {$lc};"
                }}
            "></div>
            @endif

        </div>
        @endforeach

    </div>
    @endforeach

    </div>{{-- inline-flex --}}
    </div>{{-- overflow-x --}}
    @endif

    {{-- ══ POOLS ══ --}}
    @if($draw->use_pools && $draw->pools)
    @php $pools=$draw->pools['pools']??[]; $finals=$draw->pools['finals']??[]; @endphp

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;margin-bottom:3rem;">
        @foreach($pools as $pool)
        @php
            $poolWins=[];
            foreach($pool['athletes'] as $a){ $poolWins[$a['id']]=0; }
            foreach($pool['matches'] as $m){ if(!empty($m['winner_id'])) $poolWins[$m['winner_id']]=($poolWins[$m['winner_id']]??0)+1; }
            arsort($poolWins);
        @endphp
        <div style="background:#0b0b0f;border:1px solid rgba(255,255,255,0.07);overflow:hidden;position:relative;">
            <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:{{ $genderColor }};opacity:0.5;"></div>
            <div style="padding:11px 14px 11px 17px;border-bottom:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:0.58rem;font-weight:700;color:#f59e0b;text-transform:uppercase;letter-spacing:0.2em;font-family:'Space Grotesk',sans-serif;">{{ $pool['name'] }}</span>
                <span style="font-size:0.54rem;color:rgba(255,255,255,0.18);text-transform:uppercase;">{{ count($pool['athletes']) }} combattants</span>
            </div>
            <div>
                @foreach(array_keys($poolWins) as $rank => $athleteId)
                @php $athlete=collect($pool['athletes'])->firstWhere('id',$athleteId); $wins=$poolWins[$athleteId]; $r1=($rank===0); @endphp
                @if($athlete)
                <div style="padding:9px 14px 9px 17px;display:flex;align-items:center;gap:10px;border-bottom:1px solid rgba(255,255,255,0.04);background:{{ $r1&&$wins>0 ? 'rgba(245,158,11,0.04)' : 'transparent' }};">
                    <div style="width:18px;text-align:center;font-size:0.62rem;font-weight:800;color:{{ $r1&&$wins>0 ? '#f59e0b' : 'rgba(255,255,255,0.14)' }};font-family:'Space Grotesk',sans-serif;flex-shrink:0;">{{ $rank+1 }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:0.77rem;font-weight:{{ $r1&&$wins>0 ? '800':'500' }};color:{{ $r1&&$wins>0 ? '#fff':'rgba(255,255,255,0.58)' }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;font-family:'Space Grotesk',sans-serif;">{{ $athlete['name'] }}</div>
                        @if(!empty($athlete['club']))<div style="font-size:0.54rem;color:rgba(255,255,255,0.2);margin-top:1px;text-transform:uppercase;">{{ $athlete['club'] }}</div>@endif
                    </div>
                    <div style="font-size:0.68rem;font-weight:800;color:{{ $wins>0 ? '#f59e0b':'rgba(255,255,255,0.14)' }};flex-shrink:0;font-family:'Space Grotesk',sans-serif;">{{ $wins }}V</div>
                </div>
                @endif
                @endforeach
            </div>
            <div style="padding:10px 14px 10px 17px;border-top:1px solid rgba(255,255,255,0.04);">
                <div style="font-size:0.5rem;font-weight:700;color:rgba(255,255,255,0.14);text-transform:uppercase;letter-spacing:0.2em;margin-bottom:8px;font-family:'Space Grotesk',sans-serif;">Combats</div>
                @foreach($pool['matches'] as $mi => $match)
                @php $a1=$match['athlete1']??null; $a2=$match['athlete2']??null; $wid=$match['winner_id']??null; @endphp
                <div style="display:flex;align-items:center;gap:8px;padding:5px 0;{{ $mi<count($pool['matches'])-1 ? 'border-bottom:1px solid rgba(255,255,255,0.03);':'' }}">
                    <span style="font-size:0.5rem;color:rgba(255,255,255,0.14);width:18px;flex-shrink:0;font-family:'Space Grotesk',sans-serif;font-weight:700;">C{{ $mi+1 }}</span>
                    <span style="font-size:0.74rem;font-weight:{{ $wid&&$a1&&($a1['id']??null)==$wid?'700':'400' }};color:{{ $wid&&$a1&&($a1['id']??null)==$wid?'#f59e0b':'rgba(255,255,255,0.48)' }};flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;">{{ $a1['name']??'—' }}</span>
                    <span style="font-size:0.48rem;font-weight:800;color:rgba(255,255,255,0.14);flex-shrink:0;letter-spacing:0.12em;">VS</span>
                    <span style="font-size:0.74rem;font-weight:{{ $wid&&$a2&&($a2['id']??null)==$wid?'700':'400' }};color:{{ $wid&&$a2&&($a2['id']??null)==$wid?'#f59e0b':'rgba(255,255,255,0.48)' }};flex:1;text-align:right;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;">{{ $a2['name']??'—' }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    @if(count($finals))
    <div style="border-top:1px solid rgba(245,158,11,0.1);padding-top:2.5rem;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:2rem;">
            <div style="width:22px;height:2px;background:#f59e0b;"></div>
            <span style="font-size:0.56rem;font-weight:700;color:rgba(245,158,11,0.65);text-transform:uppercase;letter-spacing:0.3em;font-family:'Space Grotesk',sans-serif;">Phase finale</span>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:3rem;align-items:flex-start;">
        @php $finalsByPhase=collect($finals)->groupBy('pool'); @endphp
        @foreach($finalsByPhase as $phase => $phaseMatches)
        <div style="min-width:260px;">
            <div style="font-size:0.54rem;font-weight:700;color:{{ $phase==='FINALE'?'#f59e0b':'rgba(255,255,255,0.28)' }};text-transform:uppercase;letter-spacing:0.22em;margin-bottom:0.75rem;padding-bottom:0.5rem;border-bottom:{{ $phase==='FINALE'?'2px solid rgba(245,158,11,0.4)':'1px solid rgba(255,255,255,0.06)' }};font-family:'Space Grotesk',sans-serif;">{{ $phase }}</div>
            @foreach($phaseMatches as $match)
            @php
                $a1=$match['athlete1']??null; $a2=$match['athlete2']??null; $wid=$match['winner_id']??null;
                $ph1=!empty($a1['placeholder']); $ph2=!empty($a2['placeholder']);
                $a1w=$wid&&$a1&&!$ph1&&($a1['id']??null)==$wid;
                $a2w=$wid&&$a2&&!$ph2&&($a2['id']??null)==$wid;
                $hw=(bool)$wid;
            @endphp
            <div style="border:{{ $phase==='FINALE' ? '2px solid rgba(245,158,11,0.4)' : '1px solid rgba(255,255,255,0.1)' }};overflow:hidden;margin-bottom:0.5rem;box-shadow:{{ $phase==='FINALE'?'0 0 24px rgba(245,158,11,0.08)':'none' }};">
                <div style="height:52px;display:flex;align-items:center;border-bottom:2px solid #06060a;background:{{ $a1w?'#1c1200':($hw?'#080810':'#0e0e18') }};">
                    <div style="width:6px;height:100%;flex-shrink:0;background:{{ $a1w?'#f59e0b':($phase==='FINALE'?'rgba(245,158,11,0.4)':$genderColor) }};{{ $a1w?'':'opacity:0.6;' }}"></div>
                    <span style="flex:1;padding:0 12px;font-size:0.8rem;font-weight:{{ $a1w?'800':'500' }};color:{{ $a1w?'#f59e0b':($ph1?'rgba(255,255,255,0.2)':($hw?'rgba(255,255,255,0.25)':'rgba(255,255,255,0.85)')) }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;font-family:'Space Grotesk',sans-serif;">{{ $a1['name']??'—' }}</span>
                    @if($a1w)<svg style="width:13px;height:13px;color:#f59e0b;flex-shrink:0;margin-right:10px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>@endif
                </div>
                <div style="height:52px;display:flex;align-items:center;background:{{ $a2w?'#1c1200':($hw?'#080810':'#0e0e18') }};">
                    <div style="width:6px;height:100%;flex-shrink:0;background:{{ $a2w?'#f59e0b':($phase==='FINALE'?'rgba(245,158,11,0.4)':$genderColor) }};{{ $a2w?'':'opacity:0.6;' }}"></div>
                    <span style="flex:1;padding:0 12px;font-size:0.8rem;font-weight:{{ $a2w?'800':'500' }};color:{{ $a2w?'#f59e0b':($ph2?'rgba(255,255,255,0.2)':($hw?'rgba(255,255,255,0.25)':'rgba(255,255,255,0.85)')) }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;font-family:'Space Grotesk',sans-serif;">{{ $a2['name']??'—' }}</span>
                    @if($a2w)<svg style="width:13px;height:13px;color:#f59e0b;flex-shrink:0;margin-right:10px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>@endif
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
        </div>
    </div>
    @endif
    @endif

</div>
@endforeach

</div>
</div>
@endif

</div>
</x-public-layout>
