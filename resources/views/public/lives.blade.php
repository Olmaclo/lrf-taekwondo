<x-public-layout title="Directs" description="Suivez les compétitions de Taekwondo en direct — Ligue de Fatick">

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    {{-- Header --}}
    <div style="background: #000; padding: 5rem 0 3rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 700px; height: 300px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(239,68,68,0.08) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: rgba(255,255,255,0.05);"></div>
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 1.25rem;">
                <span style="color: #ef4444; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Diffusions</span>
            </div>
            <h1 style="font-size: clamp(2.5rem, 6vw, 5rem); font-weight: 700; color: #fff; line-height: 1; letter-spacing: -0.03em; margin: 0 0 1.25rem; font-family: 'Space Grotesk', sans-serif;">En direct</h1>
            <p style="color: rgba(255,255,255,0.3); font-size: 0.95rem; max-width: 32rem;">Suivez les compétitions en direct et revivez les combats en replay.</p>
        </div>
    </div>

    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem 6rem;">

        {{-- ── En direct maintenant ──────────────────────────────────────────── --}}
        @if($liveNow->count())
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem;">
            <span class="live-dot-list"></span>
            <h2 style="color: #fff; font-size: 1.1rem; font-weight: 700; font-family: 'Space Grotesk', sans-serif; margin: 0;">En direct maintenant</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 4rem;">
            @foreach($liveNow as $live)
            @include('public._live-card', ['live' => $live, 'isLive' => true])
            @endforeach
        </div>
        @endif

        {{-- ── Replays ───────────────────────────────────────────────────────── --}}
        @if($replays->count())
        <div style="margin-bottom: 1.5rem;">
            <h2 style="color: rgba(255,255,255,0.7); font-size: 1.1rem; font-weight: 700; font-family: 'Space Grotesk', sans-serif; margin: 0;">Replays</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
            @foreach($replays as $live)
            @include('public._live-card', ['live' => $live, 'isLive' => false])
            @endforeach
        </div>
        @endif

        {{-- ── Vide ──────────────────────────────────────────────────────────── --}}
        @if(!$liveNow->count() && !$replays->count())
        <div style="display: flex; flex-direction: column; align-items: center; padding: 5rem 0; text-align: center;">
            <div style="width: 64px; height: 64px; border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                <svg style="width: 28px; height: 28px; color: #333;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
            </div>
            <h3 style="color: #737373; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">Aucune diffusion pour le moment</h3>
            <p style="color: #333; font-size: 0.875rem;">Les prochains directs apparaîtront ici. Reviens bientôt ! 🥋</p>
        </div>
        @endif
    </div>
</div>

@push('head')
<style>
    .live-dot-list { width: 10px; height: 10px; border-radius: 50%; background: #ef4444; display: inline-block; animation: liveDotList 1.6s infinite; }
    @keyframes liveDotList {
        0%   { box-shadow: 0 0 0 0 rgba(239,68,68,0.6); }
        70%  { box-shadow: 0 0 0 9px rgba(239,68,68,0); }
        100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
    }
</style>
@endpush

</x-public-layout>
