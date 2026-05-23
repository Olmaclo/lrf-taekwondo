<x-public-layout title="Accueil" description="Ligue Régionale de Taekwondo de Fatick — Événements, résultats et actualités">

{{-- ── HERO ─────────────────────────────────────────────────────────────────── --}}
<section style="background: #000; min-height: 100svh; display: flex; flex-direction: column; position: relative; overflow: hidden;">

    {{-- Background: gallery photo linked to next event → event cover → none --}}
    @php $heroBg = $heroPhoto?->url ?? $nextEvent?->cover_url ?? null; @endphp
    @if($heroBg)
    <div style="position: absolute; inset: 0; z-index: 0;">
        <img src="{{ $heroBg }}" alt="Prochain événement"
             style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;">
        <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.60) 50%, rgba(0,0,0,0.78) 100%);"></div>
        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, transparent 40%, rgba(0,0,0,0.5) 100%);"></div>
    </div>
    @endif

    {{-- Grid texture --}}
    <div style="position: absolute; inset: 0; z-index: 1; pointer-events: none; background-image: linear-gradient(rgba(255,255,255,.014) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.014) 1px, transparent 1px); background-size: 80px 80px;" aria-hidden></div>

    {{-- Gold radial glow --}}
    <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 100%; height: 380px; z-index: 1; pointer-events: none; background: radial-gradient(ellipse 55% 100% at 50% 0%, rgba(245,158,11,0.12) 0%, transparent 70%);" aria-hidden></div>

    {{-- Corner brackets --}}
    <div style="position: absolute; top: 100px; right: 60px; width: 40px; height: 40px; border-top: 1px solid rgba(245,158,11,0.3); border-right: 1px solid rgba(245,158,11,0.3); z-index: 2; pointer-events: none;" aria-hidden></div>
    <div style="position: absolute; bottom: 160px; left: 60px; width: 40px; height: 40px; border-bottom: 1px solid rgba(245,158,11,0.18); border-left: 1px solid rgba(245,158,11,0.18); z-index: 2; pointer-events: none;" aria-hidden></div>

    {{-- Content --}}
    <div class="px-6 sm:px-12 lg:px-20" style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column; justify-content: center; max-width: 1280px; width: 100%; margin: 0 auto; padding-top: 150px; padding-bottom: 80px;" data-gsap="hero">

        {{-- Eyebrow --}}
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 2.5rem;" data-gsap-item>
            <span style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.5); letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">00</span>
            <div style="width: 36px; height: 1px; background: rgba(245,158,11,0.4); flex-shrink: 0;"></div>
            <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Ligue Régionale de Fatick</span>
        </div>

        {{-- Main heading --}}
        <h1 style="font-weight: 700; line-height: 0.92; letter-spacing: -0.03em; margin-bottom: 3rem; font-family: 'Space Grotesk', sans-serif;" data-gsap-item>
            <span style="display: block; color: #ffffff; font-size: clamp(3rem, 8vw, 8.5rem);">L'excellence</span>
            <span style="display: block; color: #ffffff; font-size: clamp(3rem, 8vw, 8.5rem);">du sport.</span>
            <span style="display: block; font-size: clamp(3rem, 8vw, 8.5rem); -webkit-text-stroke: 1.5px #f59e0b; color: transparent;">Taekwondo.</span>
        </h1>

        {{-- Sub + CTA --}}
        <div style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 2rem;" data-gsap-item>
            <div style="flex: 1; min-width: 240px;">
                <p style="color: rgba(255,255,255,0.45); font-size: 1rem; line-height: 1.75; max-width: 26rem; margin: 0 0 2rem;">
                    Inscrivez vos athlètes, suivez les compétitions et consultez les résultats officiels.
                </p>
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1rem;">
                    <a href="{{ route('public.inscription') }}"
                       style="display: inline-flex; align-items: center; gap: 10px; background: #f59e0b; color: #000; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.06em; text-transform: uppercase; padding: 14px 28px; text-decoration: none; transition: background 0.2s, box-shadow 0.2s; clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%);"
                       onmouseover="this.style.background='#fbbf24'; this.style.boxShadow='0 0 28px rgba(245,158,11,0.4)'" onmouseout="this.style.background='#f59e0b'; this.style.boxShadow='none'">
                        Inscrire un athlète
                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('public.events') }}"
                       style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.4); font-size: 0.8rem; font-weight: 500; text-decoration: none; transition: color 0.2s; letter-spacing: 0.04em;"
                       onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                        Voir les événements →
                    </a>
                </div>
            </div>

            {{-- Next event card --}}
            @if($nextEvent)
            <div style="flex-shrink: 0; min-width: 260px; max-width: 300px; overflow: hidden; background: rgba(255,255,255,0.04); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-top: 2px solid #f59e0b; transition: background 0.2s, border-color 0.2s;"
                 onmouseover="this.style.background='rgba(245,158,11,0.06)'; this.style.borderColor='rgba(245,158,11,0.4)'" onmouseout="this.style.background='rgba(255,255,255,0.04)'; this.style.borderColor='rgba(255,255,255,0.1)'">

                {{-- Cover image --}}
                @if($nextEvent->cover_url)
                <a href="{{ route('public.event-detail', $nextEvent->slug) }}" style="display: block; text-decoration: none;">
                    <div style="aspect-ratio: 16/7; overflow: hidden; position: relative;">
                        <img src="{{ $nextEvent->cover_url }}" alt="{{ $nextEvent->name }}"
                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;"
                             onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.55) 100%);"></div>
                    </div>
                </a>
                @endif

                <a href="{{ route('public.event-detail', $nextEvent->slug) }}"
                   style="display: flex; flex-direction: column; gap: 10px; padding: 1.25rem 1.5rem {{ $nextEvent->cover_url ? '' : '' }}; text-decoration: none;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.6rem; font-weight: 700; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.14em;">
                            <span style="width: 5px; height: 5px; border-radius: 50%; background: #f59e0b; display: inline-block; flex-shrink: 0;"></span>
                            {{ $nextEvent->status === 'open' ? 'Inscriptions ouvertes' : 'Prochain événement' }}
                        </span>
                    </div>
                    <div style="font-size: 1rem; font-weight: 700; color: #fff; line-height: 1.3; font-family: 'Space Grotesk', sans-serif;">
                        {{ $nextEvent->name }}
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <div style="display: flex; align-items: center; gap: 6px; font-size: 0.72rem; color: rgba(255,255,255,0.45);">
                            <svg style="width: 11px; height: 11px; color: #f59e0b; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75"/></svg>
                            {{ $nextEvent->start_date->format('d M Y') }}
                        </div>
                        @if($nextEvent->location)
                        <div style="display: flex; align-items: center; gap: 6px; font-size: 0.72rem; color: rgba(255,255,255,0.45);">
                            <svg style="width: 11px; height: 11px; color: #f59e0b; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            {{ $nextEvent->location }}
                        </div>
                        @endif
                    </div>
                    {{-- Countdown --}}
                    @if($nextEvent->start_date->isFuture())
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; margin-top: 2px;">
                        <div style="background: rgba(0,0,0,0.25); border: 1px solid rgba(245,158,11,0.15); border-radius: 6px; padding: 7px 4px; text-align: center;">
                            <div id="cd-days" style="font-size: 1.05rem; font-weight: 700; color: #f59e0b; font-family: 'Space Grotesk', sans-serif; line-height: 1;">00</div>
                            <div style="font-size: 0.46rem; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.08em; margin-top: 3px;">Jours</div>
                        </div>
                        <div style="background: rgba(0,0,0,0.25); border: 1px solid rgba(245,158,11,0.15); border-radius: 6px; padding: 7px 4px; text-align: center;">
                            <div id="cd-hours" style="font-size: 1.05rem; font-weight: 700; color: #f59e0b; font-family: 'Space Grotesk', sans-serif; line-height: 1;">00</div>
                            <div style="font-size: 0.46rem; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.08em; margin-top: 3px;">Heures</div>
                        </div>
                        <div style="background: rgba(0,0,0,0.25); border: 1px solid rgba(245,158,11,0.15); border-radius: 6px; padding: 7px 4px; text-align: center;">
                            <div id="cd-min" style="font-size: 1.05rem; font-weight: 700; color: #f59e0b; font-family: 'Space Grotesk', sans-serif; line-height: 1;">00</div>
                            <div style="font-size: 0.46rem; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.08em; margin-top: 3px;">Min</div>
                        </div>
                        <div style="background: rgba(0,0,0,0.25); border: 1px solid rgba(245,158,11,0.15); border-radius: 6px; padding: 7px 4px; text-align: center;">
                            <div id="cd-sec" style="font-size: 1.05rem; font-weight: 700; color: #f59e0b; font-family: 'Space Grotesk', sans-serif; line-height: 1;">00</div>
                            <div style="font-size: 0.46rem; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.08em; margin-top: 3px;">Sec</div>
                        </div>
                    </div>
                    @endif
                    @if($nextEvent->status === 'open')
                    <span style="display: inline-flex; align-items: center; gap: 5px; margin-top: 4px; font-size: 0.65rem; font-weight: 700; color: #000; background: #f59e0b; padding: 6px 14px; letter-spacing: 0.06em; text-transform: uppercase; clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%);">
                        S'inscrire →
                    </span>
                    @endif
                </a>
            </div>
            @if($nextEvent->start_date->isFuture())
            <script>
            (function () {
                var target = new Date('{{ $nextEvent->start_date->toDateString() }}').getTime();
                function tick() {
                    var diff = target - Date.now();
                    if (diff <= 0) { clearInterval(timer); return; }
                    document.getElementById('cd-days').textContent  = String(Math.floor(diff / 86400000)).padStart(2, '0');
                    document.getElementById('cd-hours').textContent = String(Math.floor(diff % 86400000 / 3600000)).padStart(2, '0');
                    document.getElementById('cd-min').textContent   = String(Math.floor(diff % 3600000 / 60000)).padStart(2, '0');
                    document.getElementById('cd-sec').textContent   = String(Math.floor(diff % 60000 / 1000)).padStart(2, '0');
                }
                tick();
                var timer = setInterval(tick, 1000);
            })();
            </script>
            @endif
            @endif
        </div>
    </div>

    {{-- Stats strip --}}
    <div style="position: relative; z-index: 2; border-top: 1px solid rgba(255,255,255,0.08);">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); border-left: 1px solid rgba(255,255,255,0.08);" data-gsap="stagger">
                @php
                    $statItems = [
                        ['value' => $stats['events'], 'label' => 'Compétitions', 'suffix' => ''],
                        ['value' => 14,               'label' => 'Clubs affiliés', 'suffix' => ''],
                    ];
                @endphp
                @foreach($statItems as $s)
                <div style="padding: 2rem 2rem; border-right: 1px solid rgba(255,255,255,0.08); position: relative;" data-gsap-item>
                    <div style="position: absolute; top: 0; left: 2rem; width: 20px; height: 2px; background: #f59e0b;"></div>
                    <div style="font-size: clamp(1.8rem, 3.5vw, 3.2rem); font-weight: 700; color: #fff; line-height: 1; margin-bottom: 6px; font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.04em;">
                        <span data-gsap="counter" data-target="{{ $s['value'] }}">{{ $s['value'] }}</span><span style="color: #f59e0b; font-size: 0.6em;">{{ $s['suffix'] }}</span>
                    </div>
                    <div style="font-size: 0.6rem; font-weight: 700; color: rgba(255,255,255,0.25); text-transform: uppercase; letter-spacing: 0.16em; font-family: 'Space Grotesk', sans-serif;">{{ $s['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── UPCOMING EVENTS ──────────────────────────────────────────────────────── --}}
@if($upcomingEvents->count())
<section style="background: #000; padding: 7rem 0 8rem;">
    {{-- Section separator --}}
    <div style="height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06) 20%, rgba(255,255,255,0.06) 80%, transparent); margin-bottom: 7rem;"></div>

    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem;">

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <span style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.4); letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">01</span>
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Agenda</span>
            </div>
            <a href="{{ route('public.events') }}"
               style="color: rgba(255,255,255,0.25); font-size: 0.65rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; text-decoration: none; transition: color 0.2s; white-space: nowrap;"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">
                Tout voir →
            </a>
        </div>

        <h2 style="font-size: clamp(2.5rem, 5vw, 5.5rem); font-weight: 700; color: #fff; line-height: 1; letter-spacing: -0.03em; margin-bottom: 3.5rem; font-family: 'Space Grotesk', sans-serif;">
            Prochains<br>événements
        </h2>

        <div style="border-top: 1px solid rgba(255,255,255,0.07);" data-gsap="stagger">
            @foreach($upcomingEvents as $i => $event)
            <a href="{{ route('public.event-detail', $event->slug) }}"
               style="display: flex; align-items: center; gap: 2rem; padding: 1.75rem 1rem 1.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); text-decoration: none; transition: all 0.2s; cursor: pointer; position: relative;"
               onmouseover="this.style.paddingLeft='1.25rem'; this.style.borderBottomColor='rgba(245,158,11,0.2)'; this.querySelector('.ev-name').style.color='#f59e0b'; this.querySelector('.ev-arrow').style.color='#f59e0b'; this.querySelector('.ev-arrow').style.opacity='1'"
               onmouseout="this.style.paddingLeft='0'; this.style.borderBottomColor='rgba(255,255,255,0.05)'; this.querySelector('.ev-name').style.color='#fff'; this.querySelector('.ev-arrow').style.color='rgba(255,255,255,0.2)'; this.querySelector('.ev-arrow').style.opacity='0.6'"
               data-gsap-item>

                {{-- Gold left bar on hover via a pseudo element workaround --}}
                <span style="position: absolute; left: 0; top: 0; bottom: 0; width: 2px; background: #f59e0b; opacity: 0; transition: opacity 0.2s;" class="ev-bar"></span>

                <span style="font-size: 2.5rem; font-weight: 700; color: rgba(255,255,255,0.05); width: 3.5rem; flex-shrink: 0; line-height: 1; font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.05em;">
                    {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                </span>

                <span style="font-size: 0.58rem; font-weight: 700; color: rgba(255,255,255,0.25); text-transform: uppercase; letter-spacing: 0.16em; width: 5rem; flex-shrink: 0; font-family: 'Space Grotesk', sans-serif;">
                    {{ $event->type_label }}
                </span>

                <div style="flex: 1; min-width: 0;">
                    <div class="ev-name" style="font-size: 1.1rem; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; transition: color 0.2s; font-family: 'Space Grotesk', sans-serif;">
                        {{ $event->name }}
                    </div>
                    @if($event->location)
                    <div style="font-size: 0.75rem; color: rgba(255,255,255,0.3); margin-top: 3px;">{{ $event->location }}</div>
                    @endif
                </div>

                <div style="text-align: right; flex-shrink: 0;">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #fff; font-family: 'Space Grotesk', sans-serif;">{{ $event->start_date->format('d M') }}</div>
                    <div style="font-size: 0.7rem; color: rgba(255,255,255,0.3);">{{ $event->start_date->format('Y') }}</div>
                </div>

                <div style="flex-shrink: 0; margin-left: 1rem; width: 90px; text-align: right;">
                    @if($event->status === 'open')
                    <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.62rem; font-weight: 700; color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); background: rgba(245,158,11,0.06); padding: 4px 12px; border-radius: 99px; letter-spacing: 0.08em; text-transform: uppercase;">
                        <span style="width: 5px; height: 5px; border-radius: 50%; background: #f59e0b; flex-shrink: 0;"></span>
                        Ouvert
                    </span>
                    @else
                    <span style="font-size: 0.62rem; color: rgba(255,255,255,0.22); border: 1px solid rgba(255,255,255,0.08); padding: 4px 12px; border-radius: 99px; letter-spacing: 0.08em; text-transform: uppercase;">
                        {{ $event->status_label }}
                    </span>
                    @endif
                </div>

                <svg class="ev-arrow" style="width: 16px; height: 16px; color: rgba(255,255,255,0.2); flex-shrink: 0; opacity: 0.6; transition: color 0.2s, opacity 0.2s;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            @endforeach
        </div>

    </div>
</section>
@endif

{{-- ── GALLERY ──────────────────────────────────────────────────────────────── --}}
@if($recentPhotos->count())
<section style="background: #050505; padding-top: 7rem;">
    <div style="height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05) 20%, rgba(255,255,255,0.05) 80%, transparent); margin-bottom: 5rem;"></div>

    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; padding-bottom: 3rem;">
        <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 3rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 2rem;">
                    <span style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.4); letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">02</span>
                    <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                    <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Galerie</span>
                </div>
                <h2 style="font-size: clamp(2.5rem, 5vw, 5.5rem); font-weight: 700; color: #fff; line-height: 1; letter-spacing: -0.03em; font-family: 'Space Grotesk', sans-serif;">
                    Nos moments<br>forts
                </h2>
            </div>
            <a href="{{ route('public.gallery') }}"
               style="color: rgba(255,255,255,0.22); font-size: 0.65rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; text-decoration: none; transition: color 0.2s; align-self: flex-start; margin-top: 4px;"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.22)'">
                Tout voir →
            </a>
        </div>
    </div>

    {{-- Full-bleed photo grid --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px;" data-gsap="stagger">
        @foreach($recentPhotos->take(9) as $i => $photo)
        <a href="{{ route('public.gallery') }}"
           style="display: block; position: relative; overflow: hidden; aspect-ratio: 1; {{ $i === 0 ? 'grid-column: span 2; grid-row: span 2; aspect-ratio: unset;' : '' }}"
           data-gsap-item>
            <img src="{{ $photo->url }}"
                 alt="{{ $photo->caption ?? 'Photo' }}"
                 loading="lazy"
                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94), filter 0.4s ease; display: block; {{ $i === 0 ? 'height: 100%; min-height: 400px;' : '' }}"
                 onmouseover="this.style.transform='scale(1.06)'; this.style.filter='brightness(1.08)'"
                 onmouseout="this.style.transform='scale(1)'; this.style.filter='brightness(1)'">
            {{-- Hover overlay --}}
            <div style="position: absolute; inset: 0; background: rgba(245,158,11,0); transition: background 0.4s; pointer-events: none;"
                 onmouseover="this.style.background='rgba(245,158,11,0.06)'" onmouseout="this.style.background='rgba(245,158,11,0)'"></div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ── BLOG ─────────────────────────────────────────────────────────────────── --}}
@if($latestPosts->count())
<section style="background: #000; padding: 7rem 0 8rem;">
    <div style="height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06) 20%, rgba(255,255,255,0.06) 80%, transparent); margin-bottom: 7rem;"></div>

    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem;">

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <span style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.4); letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">03</span>
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Actualités</span>
            </div>
            <a href="{{ route('public.blog') }}"
               style="color: rgba(255,255,255,0.25); font-size: 0.65rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; text-decoration: none; transition: color 0.2s; white-space: nowrap;"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">
                Tout voir →
            </a>
        </div>

        <h2 style="font-size: clamp(2.5rem, 5vw, 5.5rem); font-weight: 700; color: #fff; line-height: 1; letter-spacing: -0.03em; margin-bottom: 4rem; font-family: 'Space Grotesk', sans-serif;">
            Dernières<br>actualités
        </h2>

        {{-- Editorial grid --}}
        <div style="display: grid; grid-template-columns: {{ $latestPosts->count() > 1 ? '1fr 1fr' : '1fr' }}; gap: 1px; background: rgba(255,255,255,0.06);" data-gsap="stagger">

            {{-- Featured article --}}
            @php $featured = $latestPosts->first() @endphp
            <a href="{{ route('public.blog-post', $featured->slug) }}"
               style="background: #000; padding: 2.5rem 4rem; display: flex; flex-direction: column; gap: 1.25rem; text-decoration: none; transition: background 0.2s; position: relative; overflow: hidden;"
               onmouseover="this.style.background='#060606'" onmouseout="this.style.background='#000'"
               data-gsap-item>
                {{-- Gold top accent --}}
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, #f59e0b 0%, transparent 60%);"></div>
                @if($featured->cover_url)
                <div style="aspect-ratio: 16/9; overflow: hidden; margin-bottom: 0.25rem; position: relative;">
                    <img src="{{ $featured->cover_url }}" alt="{{ $featured->title }}"
                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;"
                         onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                @endif
                <div style="display: flex; gap: 10px; align-items: center; font-size: 0.7rem; color: rgba(255,255,255,0.3);">
                    @if($featured->author)
                    <span style="font-weight: 600; color: rgba(255,255,255,0.45);">{{ $featured->author->name }}</span>
                    <span style="color: rgba(255,255,255,0.15);">·</span>
                    @endif
                    <span>{{ $featured->published_at?->format('d M Y') }}</span>
                </div>
                <h3 style="font-size: 1.35rem; font-weight: 700; color: #fff; line-height: 1.25; transition: color 0.2s; font-family: 'Space Grotesk', sans-serif;"
                    onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#fff'">
                    {{ $featured->title }}
                </h3>
                <p style="color: rgba(255,255,255,0.3); font-size: 0.82rem; line-height: 1.75; flex: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $featured->excerpt_auto }}
                </p>
                <span style="display: inline-flex; align-items: center; gap: 6px; color: #f59e0b; font-size: 0.62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em;">
                    Lire l'article
                    <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </a>

            {{-- 2 secondary articles --}}
            @if($latestPosts->count() > 1)
            <div style="display: grid; grid-template-rows: 1fr 1fr; gap: 1px; background: rgba(255,255,255,0.06);">
                @foreach($latestPosts->skip(1)->take(2) as $post)
                <a href="{{ route('public.blog-post', $post->slug) }}"
                   style="background: #000; padding: 2rem 3.5rem; display: flex; flex-direction: column; gap: 1rem; text-decoration: none; transition: background 0.2s; position: relative; overflow: hidden;"
                   onmouseover="this.style.background='#060606'" onmouseout="this.style.background='#000'"
                   data-gsap-item>
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, rgba(245,158,11,0.3) 0%, transparent 50%);"></div>
                    <div style="display: flex; gap: 10px; align-items: center; font-size: 0.68rem; color: rgba(255,255,255,0.28);">
                        @if($post->author)
                        <span style="font-weight: 600; color: rgba(255,255,255,0.4);">{{ $post->author->name }}</span>
                        <span style="color: rgba(255,255,255,0.1);">·</span>
                        @endif
                        <span>{{ $post->published_at?->format('d M Y') }}</span>
                    </div>
                    <h3 style="font-size: 1rem; font-weight: 700; color: #fff; line-height: 1.35; transition: color 0.2s; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-family: 'Space Grotesk', sans-serif;"
                        onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#fff'">
                        {{ $post->title }}
                    </h3>
                    <p style="color: rgba(255,255,255,0.28); font-size: 0.78rem; line-height: 1.65; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex: 1;">
                        {{ $post->excerpt_auto }}
                    </p>
                    <span style="display: inline-flex; align-items: center; gap: 5px; color: #f59e0b; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em;">
                        Lire
                        <svg style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </a>
                @endforeach
            </div>
            @endif
        </div>

    </div>
</section>
@endif

{{-- ── CTA BANNER ───────────────────────────────────────────────────────────── --}}
<section style="background: #f59e0b; padding: 7rem 0; position: relative; overflow: hidden;">
    {{-- Background text --}}
    <div style="position: absolute; right: -2rem; top: 50%; transform: translateY(-50%); font-size: 18vw; font-weight: 700; color: rgba(0,0,0,0.06); line-height: 1; letter-spacing: -0.05em; pointer-events: none; user-select: none; white-space: nowrap; font-family: 'Space Grotesk', sans-serif;" aria-hidden>
        TAEKWONDO
    </div>
    {{-- Decorative bracket --}}
    <div style="position: absolute; top: 40px; left: 40px; width: 32px; height: 32px; border-top: 2px solid rgba(0,0,0,0.12); border-left: 2px solid rgba(0,0,0,0.12); pointer-events: none;" aria-hidden></div>

    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; display: flex; flex-direction: column; gap: 3rem; position: relative;">
        <div>
            <p style="color: rgba(0,0,0,0.38); font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; margin-bottom: 1.25rem; font-family: 'Space Grotesk', sans-serif;">Rejoindre la compétition</p>
            <h2 style="font-size: clamp(2rem, 5vw, 4.5rem); font-weight: 700; color: #000; line-height: 1.05; letter-spacing: -0.03em; font-family: 'Space Grotesk', sans-serif;">
                Prêt à représenter<br>votre club ?
            </h2>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
            <a href="{{ route('public.inscription') }}"
               style="display: inline-flex; align-items: center; gap: 10px; background: #000; color: #fff; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.06em; text-transform: uppercase; padding: 14px 28px; text-decoration: none; transition: background 0.2s; clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%);"
               onmouseover="this.style.background='#111'" onmouseout="this.style.background='#000'">
                Inscrire un athlète
            </a>
            <a href="{{ route('register') }}"
               style="display: inline-flex; align-items: center; gap: 10px; background: transparent; color: #000; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.06em; text-transform: uppercase; padding: 14px 28px; border: 2px solid rgba(0,0,0,0.25); text-decoration: none; transition: all 0.2s;"
               onmouseover="this.style.borderColor='rgba(0,0,0,0.6)'; this.style.background='rgba(0,0,0,0.07)'" onmouseout="this.style.borderColor='rgba(0,0,0,0.25)'; this.style.background='transparent'">
                Créer un compte coach
            </a>
        </div>
    </div>
</section>

</x-public-layout>
