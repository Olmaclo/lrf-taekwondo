<x-public-layout
    :title="$event->name"
    :description="$event->description ? Str::limit(strip_tags($event->description), 160) : 'Compétition de Taekwondo — ' . $event->name . ' · ' . $event->location"
    :image="$event->cover_url"
    type="article"
>

@push('head')
{{-- Données structurées Schema.org : compétition (rich snippets Google) --}}
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context'            => 'https://schema.org',
    '@type'               => 'SportsEvent',
    'name'                => $event->name,
    'sport'               => 'Taekwondo',
    'startDate'           => optional($event->start_date)->toDateString(),
    'endDate'             => optional($event->end_date ?? $event->start_date)->toDateString(),
    'eventStatus'         => $event->status === 'cancelled'
                                ? 'https://schema.org/EventCancelled'
                                : 'https://schema.org/EventScheduled',
    'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
    'description'         => $event->description ? Str::limit(strip_tags($event->description), 300) : null,
    'image'               => $event->cover_url,
    'url'                 => url()->current(),
    'location'            => [
        '@type'   => 'Place',
        'name'    => $event->location ?: 'Fatick, Sénégal',
        'address' => [
            '@type'           => 'PostalAddress',
            'addressLocality' => $event->location ?: 'Fatick',
            'addressCountry'  => 'SN',
        ],
    ],
    'organizer'           => [
        '@type' => 'SportsOrganization',
        'name'  => 'Ligue Régionale de Taekwondo de Fatick',
        'url'   => url('/'),
    ],
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<style>
    .live-dot-banner { width: 7px; height: 7px; border-radius: 50%; background: #fff; display: inline-block; animation: liveDotBanner 1.6s infinite; }
    @keyframes liveDotBanner {
        0%   { box-shadow: 0 0 0 0 rgba(255,255,255,0.6); }
        70%  { box-shadow: 0 0 0 7px rgba(255,255,255,0); }
        100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); }
    }
</style>
@endpush

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    @if($liveSession)
    {{-- ── Bandeau EN DIRECT ──────────────────────────────────────────────── --}}
    <a href="{{ route('public.live', $liveSession) }}"
       style="display: block; text-decoration: none; background: linear-gradient(90deg, rgba(239,68,68,0.18) 0%, rgba(239,68,68,0.06) 50%, transparent 100%); border-bottom: 1px solid rgba(239,68,68,0.25);">
        <div style="max-width: 1280px; margin: 0 auto; padding: 1rem 2.5rem; display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
            <span style="display: inline-flex; align-items: center; gap: 8px; background: #ef4444; color: #fff; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; padding: 5px 12px; border-radius: 6px; font-family: 'Space Grotesk', sans-serif;">
                <span class="live-dot-banner"></span> En direct
            </span>
            <span style="color: #fff; font-weight: 600; font-size: 0.95rem; flex: 1; min-width: 200px;">{{ $liveSession->title }}</span>
            <span style="display: inline-flex; align-items: center; gap: 7px; color: #f59e0b; font-weight: 700; font-size: 0.82rem; letter-spacing: 0.02em;">
                Regarder maintenant
                <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </span>
        </div>
    </a>
    @endif

    {{-- Hero --}}
    <div style="{{ $event->cover_image ? 'background: url(\'' . $event->cover_url . '\') center/cover no-repeat;' : 'background: #000;' }} padding: 4.5rem 0; position: relative; overflow: hidden;">
        @if($event->cover_image)
        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.75); backdrop-filter: blur(1px);"></div>
        @endif
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 800px; height: 300px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(245,158,11,0.08) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: rgba(255,255,255,0.05);"></div>
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative;">
            <a href="{{ route('public.events') }}"
               style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.3); font-size: 0.75rem; text-decoration: none; margin-bottom: 2.5rem; transition: color 0.2s; letter-spacing: 0.06em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Retour aux événements
            </a>

            <div style="display: flex; flex-direction: column; gap: 2.5rem;">
                @if($event->status === 'open')
                <div style="display: flex; align-items: center; gap: 6px; font-size: 0.7rem; font-weight: 600; color: #f59e0b; width: fit-content; border: 1px solid rgba(245,158,11,0.3); background: rgba(245,158,11,0.05); padding: 5px 14px; border-radius: 99px;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b;"></span>
                    Inscriptions ouvertes
                </div>
                @else
                <span style="font-size: 0.7rem; color: #525252; border: 1px solid rgba(255,255,255,0.1); padding: 5px 14px; border-radius: 99px; width: fit-content;">{{ $event->status_label }}</span>
                @endif

                <div style="display: flex; flex-wrap: wrap; gap: 3rem; align-items: flex-start;">
                    <div style="flex: 1; min-width: 280px;">
                        <h1 style="font-size: clamp(2rem, 5vw, 4rem); font-weight: 900; color: #fff; line-height: 1.05; letter-spacing: -0.03em; margin: 0 0 1rem;">{{ $event->name }}</h1>
                        @if($event->description)
                        <p style="color: #737373; font-size: 1rem; line-height: 1.7; max-width: 36rem;">{{ $event->description }}</p>
                        @endif
                    </div>

                    {{-- Info card --}}
                    <div style="background: #0a0a0a; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 1.75rem; min-width: 280px; flex-shrink: 0;">
                        <div style="font-size: 0.65rem; font-weight: 700; color: #525252; text-transform: uppercase; letter-spacing: 0.18em; margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.07);">Informations</div>
                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            @foreach([
                                ['icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5', 'label' => 'Date', 'value' => $event->start_date->format('d M Y') . ($event->end_date && !$event->end_date->eq($event->start_date) ? ' — ' . $event->end_date->format('d M Y') : '')],
                            ] as $item)
                            <div style="display: flex; gap: 12px; align-items: flex-start;">
                                <svg style="width: 14px; height: 14px; color: #f59e0b; flex-shrink: 0; margin-top: 2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                                <div>
                                    <div style="font-size: 0.65rem; color: #525252; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.1em;">{{ $item['label'] }}</div>
                                    <div style="font-size: 0.875rem; color: #fff; font-weight: 600;">{{ $item['value'] }}</div>
                                </div>
                            </div>
                            @endforeach
                            @if($event->location)
                            <div style="display: flex; gap: 12px; align-items: flex-start;">
                                <svg style="width: 14px; height: 14px; color: #f59e0b; flex-shrink: 0; margin-top: 2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                <div>
                                    <div style="font-size: 0.65rem; color: #525252; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.1em;">Lieu</div>
                                    <div style="font-size: 0.875rem; color: #fff; font-weight: 600;">{{ $event->location }}</div>
                                </div>
                            </div>
                            @endif
                            @if($event->registration_fee)
                            <div style="display: flex; gap: 12px; align-items: flex-start;">
                                <svg style="width: 14px; height: 14px; color: #f59e0b; flex-shrink: 0; margin-top: 2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <div style="font-size: 0.65rem; color: #525252; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.1em;">Frais</div>
                                    <div style="font-size: 1.1rem; color: #f59e0b; font-weight: 800;">{{ number_format($event->registration_fee, 0, ',', ' ') }} FCFA</div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if($event->status === 'open')
                        <a href="{{ route('public.inscription') }}?event_id={{ $event->id }}"
                           style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 1.5rem; background: #f59e0b; color: #000; font-weight: 700; font-size: 0.875rem; padding: 12px 20px; border-radius: 8px; text-decoration: none; transition: background 0.2s;"
                           onmouseover="this.style.background='#fbbf24'" onmouseout="this.style.background='#f59e0b'">
                            Inscrire un athlète →
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content sections --}}
    <div style="max-width: 1280px; margin: 0 auto; padding: 4rem 2rem 6rem;">

        {{-- Quick links --}}
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 3rem;">
            <a href="{{ route('public.athlete-list', $event->slug) }}"
               style="display: inline-flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 0.78rem; font-weight: 600; padding: 12px 22px; text-decoration: none; transition: all 0.2s; font-family: 'Space Grotesk', sans-serif;"
               onmouseover="this.style.borderColor='rgba(245,158,11,0.4)'; this.style.color='#f59e0b'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='#fff'">
                <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Liste officielle des athlètes
            </a>
            <a href="{{ route('public.draws', $event->slug) }}"
               style="display: inline-flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 0.78rem; font-weight: 600; padding: 12px 22px; text-decoration: none; transition: all 0.2s; font-family: 'Space Grotesk', sans-serif;"
               onmouseover="this.style.borderColor='rgba(245,158,11,0.4)'; this.style.color='#f59e0b'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='#fff'">
                <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18m-7 5h7"/></svg>
                Tableau des tirages
            </a>
        </div>

        {{-- Categories --}}
        @if($categories->count())
        <div style="margin-bottom: 4rem;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 2rem;">
                <div style="width: 24px; height: 2px; background: #f59e0b;"></div>
                <h2 style="font-size: 1.5rem; font-weight: 900; color: #fff; margin: 0;">Catégories</h2>
                <span style="color: #525252; font-size: 0.8rem;">— {{ $categories->count() }} catégorie(s), athlètes validés</span>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1px; background: rgba(255,255,255,0.08);">
                @foreach($categories as $cat)
                @php $catGenderLabel = \App\Models\Athlete::genderLabel($cat['gender'], $cat['age_category']); @endphp
                <div style="background: #000; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; flex-shrink: 0; {{ $cat['gender'] === 'M' ? 'background: rgba(59,130,246,0.1); color: #60a5fa;' : 'background: rgba(236,72,153,0.1); color: #f472b6;' }}">
                        {{ $cat['gender'] === 'M' ? '♂' : '♀' }}
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <div style="font-weight: 700; color: #fff; font-size: 0.875rem; line-height: 1.3;">{{ $cat['age_category'] }} <span style="{{ $cat['gender'] === 'M' ? 'color: #60a5fa;' : 'color: #f472b6;' }} font-weight: 600; font-size: 0.8rem;">{{ $catGenderLabel }}</span></div>
                        <div style="color: #525252; font-size: 0.75rem;">{{ $cat['weight_category'] }}</div>
                    </div>
                    <div style="text-align: right; flex-shrink: 0;">
                        <div style="font-weight: 800; color: #f59e0b; font-size: 1.1rem; line-height: 1;">{{ $cat['count'] }}</div>
                        <div style="color: #525252; font-size: 0.65rem;">athlètes</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Rankings --}}
        @if($rankings->count())
        <div style="margin-bottom: 4rem;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 2rem;">
                <div style="width: 24px; height: 2px; background: #f59e0b;"></div>
                <h2 style="font-size: 1.5rem; font-weight: 900; color: #fff; margin: 0;">Classement</h2>
            </div>
            <div style="border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                            <th style="padding: 12px 16px; text-align: left; color: #525252; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">Pos.</th>
                            <th style="padding: 12px 16px; text-align: left; color: #525252; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">Athlète</th>
                            <th style="padding: 12px 16px; text-align: left; color: #525252; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">Catégorie</th>
                            <th style="padding: 12px 16px; text-align: left; color: #525252; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">Club</th>
                            <th style="padding: 12px 16px; text-align: right; color: #525252; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">Pts</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rankings as $rank)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.15s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 14px 16px;">
                                @if($rank->position == 1) <span style="font-size: 1.1rem;">🥇</span>
                                @elseif($rank->position == 2) <span style="font-size: 1.1rem;">🥈</span>
                                @elseif($rank->position == 3) <span style="font-size: 1.1rem;">🥉</span>
                                @else <span style="color: #525252; font-weight: 600;">{{ $rank->position }}e</span>
                                @endif
                            </td>
                            <td style="padding: 14px 16px; color: #fff; font-weight: 600;">{{ $rank->athlete ? $rank->athlete->first_name . ' ' . $rank->athlete->last_name : '—' }}</td>
                            <td style="padding: 14px 16px; color: #737373;">{{ $rank->category }}</td>
                            <td style="padding: 14px 16px; color: #737373;">{{ $rank->athlete?->club ?? '—' }}</td>
                            <td style="padding: 14px 16px; text-align: right; color: #f59e0b; font-weight: 800;">{{ $rank->points }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Photos --}}
        @if($photos->count())
        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 24px; height: 2px; background: #f59e0b;"></div>
                    <h2 style="font-size: 1.5rem; font-weight: 900; color: #fff; margin: 0;">Photos</h2>
                </div>
                <a href="{{ route('public.gallery') }}?event_id={{ $event->id }}"
                   style="color: #525252; font-size: 0.75rem; text-decoration: none; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; transition: color 0.2s;"
                   onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#525252'">
                    Voir toutes →
                </a>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 3px;"
                 x-data="{ lightbox: null }">
                @foreach($photos as $photo)
                <button @click="lightbox = '{{ $photo->url }}'"
                        style="display: block; position: relative; overflow: hidden; aspect-ratio: 1; background: #111; border: none; cursor: pointer; padding: 0;">
                    <img src="{{ $photo->url }}" alt="{{ $photo->caption ?? '' }}"
                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease, filter 0.3s ease; display: block;"
                         onmouseover="this.style.transform='scale(1.05)'; this.style.filter='brightness(1.1)'"
                         onmouseout="this.style.transform='scale(1)'; this.style.filter='brightness(1)'">
                </button>
                @endforeach
                <div x-show="lightbox" x-cloak
                     @click="lightbox = null" @keydown.escape.window="lightbox = null"
                     style="position: fixed; inset: 0; z-index: 100; background: rgba(0,0,0,0.97); display: flex; align-items: center; justify-content: center; padding: 2rem; cursor: pointer;">
                    <img :src="lightbox" style="max-width: 100%; max-height: 90vh; object-fit: contain; border-radius: 8px;" @click.stop>
                    <button @click="lightbox = null" style="position: absolute; top: 1.5rem; right: 1.5rem; background: rgba(255,255,255,0.1); border: none; color: #fff; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; display: flex; align-items: center; justify-content: center;">✕</button>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

</x-public-layout>
