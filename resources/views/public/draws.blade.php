<x-public-layout :title="'Tirages — ' . $event->name" :description="'Tirages officiels de ' . $event->name">

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    {{-- Hero --}}
    <div style="background: #000; padding: 4.5rem 0; position: relative; overflow: hidden; border-bottom: 1px solid rgba(255,255,255,0.06);">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 800px; height: 300px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(245,158,11,0.08) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative;">

            <a href="{{ route('public.event-detail', $event->slug) }}"
               style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.3); font-size: 0.72rem; text-decoration: none; margin-bottom: 2.5rem; transition: color 0.2s; letter-spacing: 0.08em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ $event->name }}
            </a>

            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 1.5rem;">
                <span style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.4); letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">01</span>
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Tirages officiels</span>
            </div>

            <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 2rem;">
                <div>
                    <h1 style="font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 700; color: #fff; line-height: 1.05; letter-spacing: -0.03em; margin: 0 0 0.75rem; font-family: 'Space Grotesk', sans-serif;">
                        Tableau des matchs
                    </h1>
                    <p style="color: rgba(255,255,255,0.3); font-size: 0.875rem; margin: 0;">
                        {{ $draws->count() }} catégorie(s) tirée(s) au sort
                    </p>
                </div>
                <a href="{{ route('public.athlete-list', $event->slug) }}"
                   style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.4); font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; text-decoration: none; border: 1px solid rgba(255,255,255,0.1); padding: 9px 18px; transition: all 0.2s;"
                   onmouseover="this.style.color='#f59e0b'; this.style.borderColor='rgba(245,158,11,0.4)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.1)'">
                    Liste des athlètes
                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- No draws --}}
    @if($draws->isEmpty())
    <div style="padding: 6rem 0; text-align: center;">
        <svg style="width: 48px; height: 48px; color: rgba(255,255,255,0.1); margin: 0 auto 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 7h18M3 12h18m-7 5h7"/></svg>
        <p style="color: rgba(255,255,255,0.25); font-size: 0.875rem;">Les tirages au sort n'ont pas encore été effectués pour cet événement.</p>
    </div>

    @else

    @php
        $roundLabels = [
            1 => 'Finale',
            2 => 'Demi-finales',
            3 => 'Quarts de finale',
            4 => 'Huitièmes',
            5 => 'Seizièmes',
        ];
    @endphp

    <div style="padding: 5rem 0 7rem;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem;">

            @php $drawNum = 0; @endphp
            @foreach($draws as $draw)
            @php
                $drawNum++;
                $genderLabel = \App\Models\Athlete::genderLabel($draw->gender, $draw->age_category ?? '');
                $genderColor = $draw->gender === 'M' ? '#60a5fa' : '#f472b6';

                // Determine winner for podium display
                $champion = null;
                $runnerUp = null;
                if (!$draw->use_pools && $draw->matches) {
                    $final = collect($draw->matches)->where('round', 1)->first();
                    if ($final && !empty($final['winner'])) {
                        $champion = $final['winner'];
                        // Runner-up = the loser of the final
                        $a1 = $final['athlete1'] ?? null;
                        $a2 = $final['athlete2'] ?? null;
                        if ($a1 && $a2 && !empty($final['winner_id'])) {
                            $runnerUp = ($a1['id'] ?? null) == $final['winner_id'] ? $a2 : $a1;
                        }
                    }
                }
                if ($draw->use_pools && $draw->pools) {
                    $poolFinals = $draw->pools['finals'] ?? [];
                    $grandFinal = collect($poolFinals)->where('pool', 'FINALE')->first();
                    if ($grandFinal && !empty($grandFinal['winner'])) {
                        $champion = $grandFinal['winner'];
                    }
                }
            @endphp

            <div style="margin-bottom: 6rem;">

                {{-- Draw header --}}
                <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 2rem; padding-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.07);">
                    <div style="width: 2.75rem; height: 2.75rem; background: rgba(245,158,11,0.06); border: 1px solid rgba(245,158,11,0.2); display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 0.9rem; color: #f59e0b; flex-shrink: 0;">
                        {{ str_pad($drawNum, 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <h2 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin: 0; font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em;">
                            {{ $draw->age_category }}
                            <span style="color: {{ $genderColor }}; font-weight: 600;">{{ $genderLabel }}</span>
                            <span style="color: rgba(255,255,255,0.5); font-weight: 400;"> — {{ $draw->weight_category }}</span>
                        </h2>
                        <p style="font-size: 0.65rem; color: rgba(255,255,255,0.25); margin: 4px 0 0; text-transform: uppercase; letter-spacing: 0.14em;">
                            {{ $draw->total_athletes }} athlète(s) &nbsp;·&nbsp; {{ $draw->use_pools ? 'Format poules' : 'Élimination directe' }}
                            @if($draw->generated_at)
                            &nbsp;·&nbsp; Tirage le {{ $draw->generated_at->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                </div>

                {{-- ── Champion podium ──────────────────────────────────── --}}
                @if($champion)
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: rgba(245,158,11,0.05); border: 1px solid rgba(245,158,11,0.2); margin-bottom: 2rem;">
                    <svg style="width: 22px; height: 22px; color: #f59e0b; flex-shrink: 0;" fill="currentColor" viewBox="0 0 24 24"><path d="M5 3h14l2 7H3L5 3zm7 18a6 6 0 006-6H6a6 6 0 006 6zM3 10h18v1H3v-1z"/><path d="M12 21c-2.76 0-5-2.24-5-5h10c0 2.76-2.24 5-5 5z"/><path fill-rule="evenodd" d="M11 13h2v2h-2v-2z" clip-rule="evenodd"/></svg>
                    <div>
                        <div style="font-size: 0.55rem; font-weight: 700; color: rgba(245,158,11,0.6); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 2px;">Champion</div>
                        <div style="font-size: 1rem; font-weight: 700; color: #f59e0b; font-family: 'Space Grotesk', sans-serif;">{{ $champion['name'] ?? '' }}</div>
                        @if(!empty($champion['club']))<div style="font-size: 0.7rem; color: rgba(255,255,255,0.35); margin-top: 1px;">{{ $champion['club'] }}</div>@endif
                    </div>
                    @if($runnerUp)
                    <div style="margin-left: 2rem; padding-left: 2rem; border-left: 1px solid rgba(255,255,255,0.07);">
                        <div style="font-size: 0.55rem; font-weight: 700; color: rgba(255,255,255,0.25); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 2px;">Finaliste</div>
                        <div style="font-size: 0.9rem; font-weight: 600; color: rgba(255,255,255,0.6);">{{ $runnerUp['name'] ?? '' }}</div>
                        @if(!empty($runnerUp['club']))<div style="font-size: 0.7rem; color: rgba(255,255,255,0.25); margin-top: 1px;">{{ $runnerUp['club'] }}</div>@endif
                    </div>
                    @endif
                </div>
                @endif

                {{-- ─── DIRECT ELIMINATION BRACKET ─────────────────────── --}}
                @if(!$draw->use_pools && $draw->matches)
                @php
                    // Show all matches except phantom (both athletes null)
                    $allMatches     = collect($draw->matches);
                    $matchesByRound = $allMatches
                        ->filter(fn($m) => ($m['athlete1'] !== null || $m['athlete2'] !== null))
                        ->groupBy('round')
                        ->sortKeysDesc();

                    $maxRound    = $matchesByRound->keys()->max();
                    $roundKeys   = $matchesByRound->keys()->values()->toArray();

                    // Bracket sizing
                    $bCardW = 264;  // match card width (px)
                    $bConnW = 40;   // connector arm width (px)
                    $bSlotH = 110;  // base slot height at the first round (px)
                    $bHdrH  = 48;   // round header height (px)
                    $lineC  = 'rgba(245,158,11,0.65)'; // bracket line color
                @endphp

                <div style="overflow-x: auto; padding-bottom: 2rem; -webkit-overflow-scrolling: touch;">
                <div style="display: inline-flex; align-items: flex-start; min-width: max-content; padding: 0 2px;">

                @foreach($matchesByRound as $round => $roundMatches)
                @php
                    $loopIdx     = array_search($round, $roundKeys);
                    $isFirst     = $loopIdx === 0;
                    $isLast      = $round === 1;
                    $slotH       = (int) round($bSlotH * pow(2, $maxRound - $round));
                    $matchesArr  = $roundMatches->sortBy('position')->values();
                    $roundLabel  = $roundLabels[$round] ?? ($round === $maxRound ? 'Premier tour' : "Tour {$round}");
                    $colW        = $bCardW + ($isFirst ? 0 : $bConnW) + ($isLast ? 0 : $bConnW);
                @endphp

                <div style="display: flex; flex-direction: column; flex-shrink: 0;">

                    {{-- Round header --}}
                    <div style="
                        width: {{ $colW }}px; height: {{ $bHdrH }}px;
                        display: flex; align-items: center; justify-content: center;
                        background: {{ $isLast ? 'rgba(245,158,11,0.1)' : 'rgba(255,255,255,0.02)' }};
                        border-bottom: 2px solid {{ $isLast ? 'rgba(245,158,11,0.55)' : 'rgba(255,255,255,0.08)' }};
                    ">
                        <span style="font-size: 0.62rem; font-weight: 800; color: {{ $isLast ? '#f59e0b' : 'rgba(255,255,255,0.4)' }}; text-transform: uppercase; letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">{{ $roundLabel }}</span>
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
                        $isBye = ($match['is_bye'] ?? false);
                        $hasWinner = (bool)$wid;
                    @endphp

                    <div style="height: {{ $slotH }}px; width: {{ $colW }}px; position: relative; display: flex; align-items: center;">

                        {{-- Left horizontal connector ─────────────────────── --}}
                        @if(!$isFirst)
                        <div style="width: {{ $bConnW }}px; height: 2px; background: {{ $lineC }}; flex-shrink: 0;"></div>
                        @endif

                        {{-- ── Match card ──────────────────────────────────── --}}
                        <div style="
                            width: {{ $bCardW }}px; flex-shrink: 0;
                            border: 2px solid {{ $isLast ? 'rgba(245,158,11,0.45)' : ($hasWinner ? 'rgba(255,255,255,0.18)' : 'rgba(255,255,255,0.12)') }};
                            background: #0a0a0a;
                            overflow: hidden;
                            box-shadow: 0 2px 12px rgba(0,0,0,0.5);
                        ">
                            {{-- Match number strip --}}
                            <div style="
                                padding: 3px 10px;
                                background: {{ $isLast ? 'rgba(245,158,11,0.1)' : 'rgba(255,255,255,0.03)' }};
                                border-bottom: 1px solid rgba(255,255,255,0.06);
                                display: flex; align-items: center; justify-content: space-between;
                            ">
                                <span style="font-size: 0.48rem; font-weight: 700; color: rgba(255,255,255,0.2); text-transform: uppercase; letter-spacing: 0.14em; font-family: 'Space Grotesk', sans-serif;">
                                    Match {{ $match['id'] ?? '' }}
                                </span>
                                @if($isBye && !$hasWinner)
                                <span style="font-size: 0.45rem; font-weight: 700; color: rgba(255,255,255,0.18); text-transform: uppercase; letter-spacing: 0.1em;">BYE</span>
                                @endif
                            </div>

                            {{-- Athlete 1 row --}}
                            <div style="
                                padding: 10px 14px;
                                border-bottom: 1px solid rgba(255,255,255,0.06);
                                display: flex; align-items: center; gap: 10px;
                                background: {{ $a1w ? 'rgba(245,158,11,0.1)' : 'transparent' }};
                                min-height: 50px;
                            ">
                                {{-- Winner indicator dot --}}
                                <div style="
                                    width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0;
                                    background: {{ $a1w ? '#f59e0b' : ($hasWinner && !$a1w ? 'rgba(255,255,255,0.08)' : 'rgba(255,255,255,0.12)') }};
                                "></div>
                                <div style="min-width: 0; flex: 1;">
                                    @if($a1 && !$ph1)
                                        <div style="
                                            font-size: 0.84rem; font-family: 'Space Grotesk', sans-serif;
                                            font-weight: {{ $a1w ? '700' : '500' }};
                                            color: {{ $a1w ? '#f59e0b' : ($hasWinner ? 'rgba(255,255,255,0.35)' : 'rgba(255,255,255,0.9)') }};
                                            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
                                            {{ $hasWinner && !$a1w ? 'text-decoration: none;' : '' }}
                                        ">{{ $a1['name'] ?? '' }}</div>
                                        @if(!empty($a1['club']))<div style="font-size: 0.58rem; color: rgba(255,255,255,0.2); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $a1['club'] }}</div>@endif
                                    @elseif($a1 && $ph1)
                                        <div style="font-size: 0.78rem; color: rgba(255,255,255,0.2); font-style: italic; font-family: 'Space Grotesk', sans-serif;">{{ $a1['name'] }}</div>
                                    @else
                                        <div style="font-size: 0.72rem; color: rgba(255,255,255,0.15); font-style: italic; letter-spacing: 0.08em; font-family: 'Space Grotesk', sans-serif;">— BYE —</div>
                                    @endif
                                </div>
                                @if($a1w)
                                <svg style="width: 14px; height: 14px; color: #f59e0b; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                @endif
                            </div>

                            {{-- Athlete 2 row --}}
                            <div style="
                                padding: 10px 14px;
                                display: flex; align-items: center; gap: 10px;
                                background: {{ $a2w ? 'rgba(245,158,11,0.1)' : 'transparent' }};
                                min-height: 50px;
                            ">
                                <div style="
                                    width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0;
                                    background: {{ $a2w ? '#f59e0b' : ($hasWinner && !$a2w ? 'rgba(255,255,255,0.08)' : 'rgba(255,255,255,0.12)') }};
                                "></div>
                                <div style="min-width: 0; flex: 1;">
                                    @if($a2 && !$ph2)
                                        <div style="
                                            font-size: 0.84rem; font-family: 'Space Grotesk', sans-serif;
                                            font-weight: {{ $a2w ? '700' : '500' }};
                                            color: {{ $a2w ? '#f59e0b' : ($hasWinner ? 'rgba(255,255,255,0.35)' : 'rgba(255,255,255,0.9)') }};
                                            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
                                        ">{{ $a2['name'] ?? '' }}</div>
                                        @if(!empty($a2['club']))<div style="font-size: 0.58rem; color: rgba(255,255,255,0.2); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $a2['club'] }}</div>@endif
                                    @elseif($a2 && $ph2)
                                        <div style="font-size: 0.78rem; color: rgba(255,255,255,0.2); font-style: italic; font-family: 'Space Grotesk', sans-serif;">{{ $a2['name'] }}</div>
                                    @else
                                        <div style="font-size: 0.72rem; color: rgba(255,255,255,0.15); font-style: italic; letter-spacing: 0.08em; font-family: 'Space Grotesk', sans-serif;">— BYE —</div>
                                    @endif
                                </div>
                                @if($a2w)
                                <svg style="width: 14px; height: 14px; color: #f59e0b; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                @endif
                            </div>
                        </div>
                        {{-- ── end match card ─────────────────────────────── --}}

                        {{-- Right bracket arm (L-shape connecting to next round) --}}
                        @if(!$isLast)
                        <div style="
                            position: absolute; right: 0; width: {{ $bConnW }}px;
                            {{ $isTopOfPair
                                ? "top: 50%; height: 50%; border-top: 2px solid {$lineC}; border-right: 2px solid {$lineC};"
                                : "top: 0; height: 50%; border-bottom: 2px solid {$lineC}; border-right: 2px solid {$lineC};"
                            }}
                        "></div>
                        @endif

                    </div>
                    @endforeach {{-- end match slots --}}

                </div>
                @endforeach {{-- end rounds --}}

                </div>
                </div>
                {{-- end direct elimination bracket --}}
                @endif

                {{-- ─── POOL ELIMINATION ────────────────────────────────── --}}
                @if($draw->use_pools && $draw->pools)
                @php
                    $pools  = $draw->pools['pools']  ?? [];
                    $finals = $draw->pools['finals'] ?? [];
                @endphp

                {{-- Pool cards --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
                    @foreach($pools as $pool)
                    @php
                        $poolWins = [];
                        foreach ($pool['athletes'] as $a) { $poolWins[$a['id']] = 0; }
                        foreach ($pool['matches'] as $m) {
                            if (!empty($m['winner_id'])) {
                                $poolWins[$m['winner_id']] = ($poolWins[$m['winner_id']] ?? 0) + 1;
                            }
                        }
                        arsort($poolWins);
                    @endphp
                    <div style="border: 1px solid rgba(255,255,255,0.08); background: #080808; overflow: hidden;">
                        <div style="padding: 12px 16px; background: rgba(245,158,11,0.05); border-bottom: 1px solid rgba(245,158,11,0.15); display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 0.65rem; font-weight: 700; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.18em; font-family: 'Space Grotesk', sans-serif;">{{ $pool['name'] }}</span>
                            <span style="font-size: 0.6rem; color: rgba(255,255,255,0.25); letter-spacing: 0.1em;">{{ count($pool['athletes']) }} athlètes</span>
                        </div>

                        {{-- Standings --}}
                        <div>
                            @foreach(array_keys($poolWins) as $rank => $athleteId)
                            @php
                                $athlete  = collect($pool['athletes'])->firstWhere('id', $athleteId);
                                $wins     = $poolWins[$athleteId];
                                $isFirst  = $rank === 0;
                                $isSecond = $rank === 1;
                            @endphp
                            @if($athlete)
                            <div style="padding: 10px 16px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid rgba(255,255,255,0.04); background: {{ $isFirst && $wins > 0 ? 'rgba(245,158,11,0.04)' : 'transparent' }};">
                                <div style="width: 20px; text-align: center; font-size: 0.7rem; font-weight: 700; color: {{ $isFirst && $wins > 0 ? '#f59e0b' : ($isSecond && $wins > 0 ? 'rgba(255,255,255,0.4)' : 'rgba(255,255,255,0.15)') }}; font-family: 'Space Grotesk', sans-serif; flex-shrink: 0;">
                                    {{ $rank + 1 }}
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 0.82rem; font-weight: {{ $isFirst && $wins > 0 ? '700' : '500' }}; color: {{ $isFirst && $wins > 0 ? '#fff' : 'rgba(255,255,255,0.7)' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $athlete['name'] }}</div>
                                    @if(!empty($athlete['club']))<div style="font-size: 0.62rem; color: rgba(255,255,255,0.25); margin-top: 1px;">{{ $athlete['club'] }}</div>@endif
                                </div>
                                <div style="font-size: 0.75rem; font-weight: 700; color: {{ $wins > 0 ? '#f59e0b' : 'rgba(255,255,255,0.2)' }}; flex-shrink: 0; font-family: 'Space Grotesk', sans-serif;">{{ $wins }}V</div>
                            </div>
                            @endif
                            @endforeach
                        </div>

                        {{-- Pool matches --}}
                        <div style="padding: 12px 16px; border-top: 1px solid rgba(255,255,255,0.05);">
                            <div style="font-size: 0.58rem; font-weight: 700; color: rgba(255,255,255,0.2); text-transform: uppercase; letter-spacing: 0.16em; margin-bottom: 10px;">Matchs</div>
                            @foreach($pool['matches'] as $mi => $match)
                            @php
                                $a1  = $match['athlete1'] ?? null;
                                $a2  = $match['athlete2'] ?? null;
                                $wid = $match['winner_id'] ?? null;
                            @endphp
                            <div style="display: flex; align-items: center; gap: 8px; padding: 6px 0; {{ $mi < count($pool['matches']) - 1 ? 'border-bottom: 1px solid rgba(255,255,255,0.03);' : '' }}">
                                <span style="font-size: 0.6rem; color: rgba(255,255,255,0.18); width: 18px; flex-shrink: 0;">M{{ $mi + 1 }}</span>
                                <span style="font-size: 0.78rem; font-weight: {{ $wid && $a1 && ($a1['id'] ?? null) == $wid ? '700' : '400' }}; color: {{ $wid && $a1 && ($a1['id'] ?? null) == $wid ? '#f59e0b' : 'rgba(255,255,255,0.55)' }}; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $a1['name'] ?? '—' }}</span>
                                <span style="font-size: 0.55rem; font-weight: 700; color: rgba(255,255,255,0.2); flex-shrink: 0; letter-spacing: 0.1em;">VS</span>
                                <span style="font-size: 0.78rem; font-weight: {{ $wid && $a2 && ($a2['id'] ?? null) == $wid ? '700' : '400' }}; color: {{ $wid && $a2 && ($a2['id'] ?? null) == $wid ? '#f59e0b' : 'rgba(255,255,255,0.55)' }}; flex: 1; text-align: right; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $a2['name'] ?? '—' }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Phase finale --}}
                @if(count($finals))
                <div style="border-top: 1px solid rgba(255,255,255,0.07); padding-top: 2rem;">
                    <div style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.5); text-transform: uppercase; letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif; margin-bottom: 1.5rem;">Phase finale</div>
                    <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                        @php $finalsByPhase = collect($finals)->groupBy('pool'); @endphp
                        @foreach($finalsByPhase as $phase => $phaseMatches)
                        <div style="flex: 1; min-width: 240px; max-width: 340px;">
                            <div style="font-size: 0.58rem; font-weight: 700; color: {{ $phase === 'FINALE' ? '#f59e0b' : 'rgba(255,255,255,0.3)' }}; text-transform: uppercase; letter-spacing: 0.18em; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(245,158,11,{{ $phase === 'FINALE' ? '0.2' : '0.08' }});">{{ $phase }}</div>
                            @foreach($phaseMatches as $match)
                            @php
                                $a1  = $match['athlete1'] ?? null;
                                $a2  = $match['athlete2'] ?? null;
                                $wid = $match['winner_id'] ?? null;
                                $ph1 = !empty($a1['placeholder']);
                                $ph2 = !empty($a2['placeholder']);
                                $a1w = $wid && $a1 && !$ph1 && ($a1['id'] ?? null) == $wid;
                                $a2w = $wid && $a2 && !$ph2 && ($a2['id'] ?? null) == $wid;
                            @endphp
                            <div style="border: 1px solid rgba(255,255,255,{{ $phase === 'FINALE' ? '0.15' : '0.07' }}); background: #080808; overflow: hidden; margin-bottom: 0.5rem; {{ $phase === 'FINALE' ? 'border-color: rgba(245,158,11,0.2);' : '' }}">
                                <div style="padding: 9px 14px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; gap: 8px; background: {{ $a1w ? 'rgba(245,158,11,0.07)' : 'transparent' }};">
                                    @if($a1w)<svg style="width:10px;height:10px;color:#f59e0b;flex-shrink:0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>@else<div style="width:10px;flex-shrink:0"></div>@endif
                                    <span style="font-size: 0.82rem; font-weight: {{ $a1w ? '700' : '400' }}; color: {{ $a1w ? '#f59e0b' : ($ph1 ? 'rgba(255,255,255,0.2)' : 'rgba(255,255,255,0.7)') }};">{{ $a1['name'] ?? '—' }}</span>
                                </div>
                                <div style="padding: 9px 14px; display: flex; align-items: center; gap: 8px; background: {{ $a2w ? 'rgba(245,158,11,0.07)' : 'transparent' }};">
                                    @if($a2w)<svg style="width:10px;height:10px;color:#f59e0b;flex-shrink:0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>@else<div style="width:10px;flex-shrink:0"></div>@endif
                                    <span style="font-size: 0.82rem; font-weight: {{ $a2w ? '700' : '400' }}; color: {{ $a2w ? '#f59e0b' : ($ph2 ? 'rgba(255,255,255,0.2)' : 'rgba(255,255,255,0.7)') }};">{{ $a2['name'] ?? '—' }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @endif
                {{-- end pool --}}

            </div>
            @endforeach

        </div>
    </div>
    @endif

</div>

</x-public-layout>
