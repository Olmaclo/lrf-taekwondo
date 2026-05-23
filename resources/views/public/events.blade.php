<x-public-layout title="Événements" description="Calendrier des compétitions — Ligue de Fatick">

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    {{-- Page header --}}
    <div style="background: #000; padding: 5.5rem 0 4.5rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 700px; height: 300px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(245,158,11,0.09) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: rgba(255,255,255,0.05);"></div>
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 1.5rem;">
                <span style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.4); letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">01</span>
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35); flex-shrink: 0;"></div>
                <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Calendrier</span>
            </div>
            <h1 style="font-size: clamp(2.5rem, 6vw, 5.5rem); font-weight: 700; color: #fff; line-height: 1; letter-spacing: -0.03em; margin: 0 0 1.25rem; font-family: 'Space Grotesk', sans-serif;">Événements</h1>
            <p style="color: rgba(255,255,255,0.3); font-size: 0.95rem; max-width: 32rem;">Compétitions, championnats et tournois organisés par la Ligue Régionale de Fatick.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div style="max-width: 1280px; margin: 0 auto; padding: 2rem 2.5rem;">
        <form method="GET" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
            <div style="position: relative; flex: 1; min-width: 200px;">
                <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #525252;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher…"
                       style="width: 100%; padding: 10px 12px 10px 38px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
            </div>
            <select name="type"
                    style="padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; outline: none; cursor: pointer;">
                <option value="" style="background:#111;">Tous les types</option>
                <option value="kyorugi" {{ request('type')==='kyorugi'?'selected':'' }} style="background:#111;">Kyorugi</option>
                <option value="poomsae" {{ request('type')==='poomsae'?'selected':'' }} style="background:#111;">Poomsae</option>
                <option value="mixed"   {{ request('type')==='mixed'  ?'selected':'' }} style="background:#111;">Mixte</option>
            </select>
            <select name="status"
                    style="padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; outline: none; cursor: pointer;">
                <option value="" style="background:#111;">Tous les statuts</option>
                <option value="upcoming" {{ request('status')==='upcoming'?'selected':'' }} style="background:#111;">À venir</option>
                <option value="open"     {{ request('status')==='open'    ?'selected':'' }} style="background:#111;">Ouvert</option>
                <option value="ongoing"  {{ request('status')==='ongoing' ?'selected':'' }} style="background:#111;">En cours</option>
                <option value="finished" {{ request('status')==='finished'?'selected':'' }} style="background:#111;">Terminé</option>
            </select>
            <button type="submit"
                    style="padding: 10px 22px; background: #f59e0b; color: #000; font-weight: 700; font-size: 0.8rem; border: none; border-radius: 8px; cursor: pointer; letter-spacing: 0.03em; transition: background 0.2s;"
                    onmouseover="this.style.background='#fbbf24'" onmouseout="this.style.background='#f59e0b'">
                Filtrer
            </button>
            @if(request()->hasAny(['search','type','status']))
            <a href="{{ route('public.events') }}"
               style="padding: 10px 18px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); font-size: 0.8rem; border-radius: 8px; text-decoration: none; transition: color 0.2s;"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                ✕ Effacer
            </a>
            @endif
        </form>
    </div>

    {{-- Events grid --}}
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem 6rem;">
        @if($events->count())
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1px; background: rgba(255,255,255,0.08);">
            @foreach($events as $event)
            <a href="{{ route('public.event-detail', $event->slug) }}"
               style="background: #000; display: flex; flex-direction: column; text-decoration: none; transition: background 0.2s; position: relative; overflow: hidden;"
               onmouseover="this.style.background='#0a0a0a'" onmouseout="this.style.background='#000'">

                {{-- Cover image (if available) --}}
                @if($event->cover_url)
                <div style="position: relative; aspect-ratio: 16/7; overflow: hidden; flex-shrink: 0;">
                    <img src="{{ $event->cover_url }}" alt="{{ $event->name }}"
                         loading="lazy"
                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; display: block;"
                         onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                    {{-- Dark gradient so text overlaid on image stays readable --}}
                    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.55) 100%);"></div>
                    {{-- Status + type badges floating on the image --}}
                    <div style="position: absolute; top: 12px; left: 14px; right: 14px; display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 0.6rem; font-weight: 700; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.14em; background: rgba(0,0,0,0.45); backdrop-filter: blur(6px); padding: 3px 9px; border-radius: 4px;">{{ $event->type_label }}</span>
                        @if($event->status === 'open')
                        <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.6rem; font-weight: 700; color: #f59e0b; background: rgba(0,0,0,0.55); backdrop-filter: blur(6px); border: 1px solid rgba(245,158,11,0.4); padding: 3px 10px; border-radius: 99px;">
                            <span style="width: 5px; height: 5px; border-radius: 50%; background: #f59e0b; flex-shrink: 0;"></span>
                            Ouvert
                        </span>
                        @else
                        <span style="font-size: 0.6rem; color: rgba(255,255,255,0.6); background: rgba(0,0,0,0.45); backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,0.15); padding: 3px 10px; border-radius: 99px;">{{ $event->status_label }}</span>
                        @endif
                    </div>
                    {{-- Gold top accent line --}}
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 2px; background: {{ $event->status === 'open' ? '#f59e0b' : ($event->status === 'ongoing' ? 'rgba(255,255,255,0.5)' : 'rgba(255,255,255,0.1)') }};"></div>
                </div>
                @endif

                {{-- Card body --}}
                <div style="display: flex; flex-direction: column; flex: 1; padding: 1.5rem 1.75rem; position: relative;">

                    {{-- Top accent line (only when no image) --}}
                    @if(!$event->cover_url)
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 2px; background: {{ $event->status === 'open' ? '#f59e0b' : ($event->status === 'ongoing' ? 'rgba(255,255,255,0.5)' : 'rgba(255,255,255,0.1)') }};"></div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; padding-top: 0.25rem;">
                        <span style="font-size: 0.65rem; font-weight: 600; color: #525252; text-transform: uppercase; letter-spacing: 0.12em;">{{ $event->type_label }}</span>
                        @if($event->status === 'open')
                        <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.65rem; font-weight: 600; color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); background: rgba(245,158,11,0.05); padding: 3px 10px; border-radius: 99px;">
                            <span style="width: 5px; height: 5px; border-radius: 50%; background: #f59e0b;"></span>
                            Ouvert
                        </span>
                        @else
                        <span style="font-size: 0.65rem; color: #525252; border: 1px solid rgba(255,255,255,0.08); padding: 3px 10px; border-radius: 99px;">{{ $event->status_label }}</span>
                        @endif
                    </div>
                    @endif

                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #fff; line-height: 1.3; margin-bottom: 0.5rem; flex: 1;">
                        {{ $event->name }}
                    </h3>
                    @if($event->description)
                    <p style="color: #525252; font-size: 0.8rem; line-height: 1.6; margin-bottom: 1.25rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $event->description }}
                    </p>
                    @endif

                    <div style="border-top: 1px solid rgba(255,255,255,0.07); padding-top: 1.25rem; display: flex; flex-direction: column; gap: 8px; margin-top: auto;">
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #737373;">
                            <svg style="width: 13px; height: 13px; color: #f59e0b; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/></svg>
                            {{ $event->start_date->format('d M Y') }}@if($event->end_date && !$event->end_date->eq($event->start_date)) — {{ $event->end_date->format('d M Y') }}@endif
                        </div>
                        @if($event->location)
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #737373;">
                            <svg style="width: 13px; height: 13px; color: #f59e0b; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            {{ $event->location }}
                        </div>
                        @endif
                        @if($event->registration_fee)
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.875rem; color: #f59e0b; font-weight: 700;">
                            <svg style="width: 13px; height: 13px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ number_format($event->registration_fee, 0, ',', ' ') }} FCFA
                        </div>
                        @endif
                    </div>

                    @if($event->status === 'open')
                    <div style="margin-top: 1.25rem;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: #f59e0b; color: #000; font-weight: 700; font-size: 0.75rem; padding: 8px 16px; border-radius: 6px; letter-spacing: 0.03em;">
                            S'inscrire →
                        </span>
                    </div>
                    @endif

                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($events->hasPages())
        <div style="margin-top: 3rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 2rem;">
            <span style="color: #525252; font-size: 0.8rem;">Page {{ $events->currentPage() }} sur {{ $events->lastPage() }}</span>
            <div style="display: flex; gap: 8px;">
                @if(!$events->onFirstPage())
                <a href="{{ $events->previousPageUrl() }}" style="padding: 8px 18px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); font-size: 0.8rem; border-radius: 6px; text-decoration: none; transition: color 0.2s, border-color 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.3)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'; this.style.borderColor='rgba(255,255,255,0.1)'">← Précédent</a>
                @endif
                @if($events->hasMorePages())
                <a href="{{ $events->nextPageUrl() }}" style="padding: 8px 18px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); font-size: 0.8rem; border-radius: 6px; text-decoration: none; transition: color 0.2s, border-color 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.3)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'; this.style.borderColor='rgba(255,255,255,0.1)'">Suivant →</a>
                @endif
            </div>
        </div>
        @endif

        @else
        <div style="display: flex; flex-direction: column; align-items: center; padding: 6rem 0; text-align: center;">
            <div style="width: 64px; height: 64px; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                <svg style="width: 28px; height: 28px; color: #333;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/></svg>
            </div>
            @if(request()->hasAny(['search', 'type', 'status']))
                <h3 style="color: #737373; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">Aucun résultat</h3>
                <p style="color: #333; font-size: 0.875rem; margin-bottom: 1.5rem;">Aucun événement ne correspond à ces critères de recherche.</p>
                <a href="{{ route('public.events') }}"
                   style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: #f59e0b; color: #000; font-size: 0.8rem; font-weight: 700; border-radius: 8px; text-decoration: none; transition: background 0.2s;"
                   onmouseover="this.style.background='#fbbf24'" onmouseout="this.style.background='#f59e0b'">
                    Effacer les filtres
                </a>
            @else
                <h3 style="color: #737373; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">Aucun événement disponible</h3>
                <p style="color: #333; font-size: 0.875rem; margin-bottom: 1.5rem;">La prochaine compétition sera bientôt annoncée. Revenez consulter le calendrier.</p>
                <a href="{{ route('public.contact') }}"
                   style="color: #f59e0b; font-size: 0.875rem; text-decoration: none; transition: opacity 0.2s;"
                   onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                    Nous contacter pour plus d'informations →
                </a>
            @endif
        </div>
        @endif
    </div>
</div>

</x-public-layout>
