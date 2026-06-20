<x-public-layout
    :title="$liveSession->title"
    :description="'Direct — ' . $liveSession->title . ' · ' . ($liveSession->event?->name ?? 'Ligue de Fatick')"
    type="video.other"
>

@php
    $isLive = $liveSession->isLive();
@endphp

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    {{-- ── Top bar : statut + titre ─────────────────────────────────────────── --}}
    <div style="border-bottom: 1px solid rgba(255,255,255,0.07); background: linear-gradient(180deg, rgba(245,158,11,0.04) 0%, transparent 100%);">
        <div style="max-width: 1400px; margin: 0 auto; padding: 1.75rem 2.5rem;">
            <a href="{{ $liveSession->event ? route('public.event-detail', $liveSession->event->slug) : route('public.events') }}"
               style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.3); font-size: 0.72rem; text-decoration: none; margin-bottom: 1.25rem; letter-spacing: 0.06em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif; transition: color 0.2s;"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ $liveSession->event?->name ?? 'Retour' }}
            </a>

            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                @if($isLive)
                <span style="display: inline-flex; align-items: center; gap: 8px; background: #ef4444; color: #fff; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; padding: 6px 14px; border-radius: 6px; font-family: 'Space Grotesk', sans-serif;">
                    <span class="live-dot"></span> EN DIRECT
                </span>
                @else
                <span style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.6); font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding: 6px 14px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.12); font-family: 'Space Grotesk', sans-serif;">
                    ▶ REPLAY
                </span>
                @endif
                <h1 style="font-size: clamp(1.3rem, 3vw, 2rem); font-weight: 700; color: #fff; margin: 0; line-height: 1.2; font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em;">
                    {{ $liveSession->title }}
                </h1>
            </div>
        </div>
    </div>

    {{-- ── Contenu : vidéo + panneau ────────────────────────────────────────── --}}
    <div style="max-width: 1400px; margin: 0 auto; padding: 1.75rem 2.5rem 5rem; display: grid; grid-template-columns: minmax(0, 1fr) 360px; gap: 1.75rem;" id="live-grid">

        {{-- Lecteur --}}
        <div>
            <div style="position: relative; aspect-ratio: 16/9; background: #0a0a0a; border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; overflow: hidden; box-shadow: 0 24px 64px rgba(0,0,0,0.6);">
                <iframe
                    src="{{ $liveSession->embed_url }}"
                    title="{{ $liveSession->title }}"
                    style="position: absolute; inset: 0; width: 100%; height: 100%; border: 0;"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen></iframe>
            </div>

            {{-- Infos sous la vidéo --}}
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-top: 1.25rem; padding: 1.25rem 0; border-bottom: 1px solid rgba(255,255,255,0.07);">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg style="width: 20px; height: 20px; color: #f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                    </div>
                    <div>
                        <div style="color: #fff; font-weight: 600; font-size: 0.95rem;">{{ $liveSession->event?->name ?? 'Ligue de Fatick' }}</div>
                        @if($isLive)
                        <div style="color: #ef4444; font-size: 0.78rem; font-weight: 600; display: flex; align-items: center; gap: 6px;"><span class="live-dot" style="width:6px;height:6px;"></span> En cours de diffusion</div>
                        @else
                        <div style="color: rgba(255,255,255,0.35); font-size: 0.78rem;">Diffusion terminée @if($liveSession->ended_at)· {{ $liveSession->ended_at->format('d/m/Y') }}@endif</div>
                        @endif
                    </div>
                </div>
                <a href="{{ $liveSession->watch_url }}" target="_blank" rel="noopener"
                   style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); font-size: 0.78rem; font-weight: 600; padding: 9px 16px; border-radius: 8px; text-decoration: none; transition: all 0.2s;"
                   onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.25)'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.borderColor='rgba(255,255,255,0.1)'">
                    <svg style="width: 15px; height: 15px; color: #ff0000;" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    YouTube
                </a>
            </div>

            @if($liveSession->description)
            <p style="color: rgba(255,255,255,0.55); font-size: 0.9rem; line-height: 1.7; margin-top: 1.25rem;">{{ $liveSession->description }}</p>
            @endif
        </div>

        {{-- Panneau latéral : chat (teaser Phase 2) --}}
        <aside style="display: flex; flex-direction: column;">
            <div style="background: #0a0a0a; border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; overflow: hidden; height: 100%; min-height: 480px; display: flex; flex-direction: column;">
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.07); display: flex; align-items: center; justify-content: space-between;">
                    <span style="color: #fff; font-weight: 700; font-size: 0.85rem; font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.02em;">💬 Chat en direct</span>
                    <span style="color: rgba(255,255,255,0.25); font-size: 0.68rem; border: 1px solid rgba(255,255,255,0.1); padding: 3px 8px; border-radius: 99px;">Bientôt</span>
                </div>
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2rem 1.5rem; gap: 1rem;">
                    <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2); display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 26px; height: 26px; color: #f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                    </div>
                    <div>
                        <div style="color: rgba(255,255,255,0.75); font-weight: 600; font-size: 0.9rem; margin-bottom: 6px;">Le chat arrive très vite</div>
                        <p style="color: rgba(255,255,255,0.3); font-size: 0.78rem; line-height: 1.6; max-width: 220px;">Discute en direct avec un pseudo, envoie des réactions et participe aux sondages pendant le combat. 🥋</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

@push('head')
<style>
    .live-dot { width: 8px; height: 8px; border-radius: 50%; background: #fff; display: inline-block; box-shadow: 0 0 0 0 rgba(255,255,255,0.7); animation: livePulse 1.6s infinite; }
    @keyframes livePulse {
        0%   { box-shadow: 0 0 0 0 rgba(239,68,68,0.7); transform: scale(1); }
        70%  { box-shadow: 0 0 0 8px rgba(239,68,68,0); transform: scale(1.1); }
        100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); transform: scale(1); }
    }
    @media (max-width: 960px) {
        #live-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endpush

</x-public-layout>
