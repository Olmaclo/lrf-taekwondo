<x-public-layout :title="'Tirages — ' . $event->name" :description="'Les tirages de ' . $event->name . ' ne sont pas encore disponibles.'">

<div style="background:#06060a;min-height:100vh;padding-top:80px;display:flex;flex-direction:column;">

{{-- HERO --}}
<div style="position:relative;overflow:hidden;border-bottom:1px solid rgba(245,158,11,0.08);flex:1;display:flex;flex-direction:column;justify-content:center;">
    {{-- Grid texture --}}
    <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(245,158,11,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(245,158,11,0.02) 1px,transparent 1px);background-size:60px 60px;pointer-events:none;"></div>
    {{-- Radial glow --}}
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:800px;height:500px;background:radial-gradient(ellipse,rgba(245,158,11,0.05) 0%,transparent 65%);pointer-events:none;"></div>
    {{-- Animated pulse ring --}}
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:400px;height:400px;pointer-events:none;">
        <div class="pulse-ring" style="position:absolute;inset:0;border:1px solid rgba(245,158,11,0.15);border-radius:50%;animation:pulseRing 3s ease-out infinite;"></div>
        <div class="pulse-ring" style="position:absolute;inset:0;border:1px solid rgba(245,158,11,0.08);border-radius:50%;animation:pulseRing 3s ease-out infinite 1s;"></div>
        <div class="pulse-ring" style="position:absolute;inset:0;border:1px solid rgba(245,158,11,0.04);border-radius:50%;animation:pulseRing 3s ease-out infinite 2s;"></div>
    </div>

    <div style="max-width:900px;margin:0 auto;padding:6rem 2.5rem;text-align:center;position:relative;">

        {{-- Back link --}}
        <a href="{{ route('public.event-detail', $event->slug) }}"
           style="display:inline-flex;align-items:center;gap:8px;color:rgba(255,255,255,0.25);font-size:0.68rem;text-decoration:none;margin-bottom:4rem;text-transform:uppercase;letter-spacing:0.14em;font-family:'Space Grotesk',sans-serif;transition:color .2s;"
           onmouseover="this.style.color='rgba(245,158,11,0.8)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">
            <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            {{ $event->name }}
        </a>

        {{-- Icon --}}
        <div style="display:inline-flex;align-items:center;justify-content:center;width:80px;height:80px;border:1px solid rgba(245,158,11,0.2);margin-bottom:2.5rem;position:relative;animation:floatIcon 4s ease-in-out infinite;">
            <div style="position:absolute;inset:-1px;border:1px solid rgba(245,158,11,0.06);transform:rotate(45deg);"></div>
            <svg style="width:36px;height:36px;color:#f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        {{-- Label --}}
        <div style="display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:1.5rem;">
            <div style="width:32px;height:2px;background:linear-gradient(to right,transparent,#f59e0b);"></div>
            <span style="font-size:0.58rem;font-weight:700;color:#f59e0b;letter-spacing:0.32em;text-transform:uppercase;font-family:'Space Grotesk',sans-serif;">Bientôt disponible</span>
            <div style="width:32px;height:2px;background:linear-gradient(to left,transparent,#f59e0b);"></div>
        </div>

        {{-- Title --}}
        <h1 style="font-size:clamp(2rem,5vw,3.5rem);font-weight:900;color:#fff;line-height:1.05;letter-spacing:-0.04em;margin:0 0 1.5rem;font-family:'Space Grotesk',sans-serif;text-transform:uppercase;">
            Tirages non encore<br>
            <span style="color:#f59e0b;">disponibles</span>
        </h1>

        {{-- Event name badge --}}
        <div style="display:inline-flex;align-items:center;gap:10px;background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.18);padding:8px 20px;margin-bottom:2.5rem;">
            <svg style="width:14px;height:14px;color:rgba(245,158,11,0.7);flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span style="font-size:0.72rem;font-weight:600;color:rgba(255,255,255,0.6);letter-spacing:0.08em;font-family:'Space Grotesk',sans-serif;">{{ $event->name }}</span>
        </div>

        {{-- Description --}}
        <p style="font-size:1rem;color:rgba(255,255,255,0.4);line-height:1.7;max-width:560px;margin:0 auto 3rem;font-weight:400;">
            Les tableaux de tirage seront publiés dès que les inscriptions seront closes et les tirages effectués. Revenez consulter cette page ultérieurement.
        </p>

        {{-- Status indicator --}}
        <div style="display:inline-flex;align-items:center;gap:10px;background:rgba(15,15,25,0.8);border:1px solid rgba(255,255,255,0.07);padding:10px 22px;margin-bottom:3rem;">
            @if($event->status === 'upcoming')
                <span style="width:7px;height:7px;border-radius:50%;background:#3b82f6;box-shadow:0 0 8px rgba(59,130,246,0.7);flex-shrink:0;animation:blink 2s ease-in-out infinite;"></span>
                <span style="font-size:0.68rem;font-weight:700;color:rgba(255,255,255,0.35);letter-spacing:0.14em;text-transform:uppercase;font-family:'Space Grotesk',sans-serif;">Événement à venir</span>
            @else
                <span style="width:7px;height:7px;border-radius:50%;background:#10b981;box-shadow:0 0 8px rgba(16,185,129,0.7);flex-shrink:0;animation:blink 2s ease-in-out infinite;"></span>
                <span style="font-size:0.68rem;font-weight:700;color:rgba(255,255,255,0.35);letter-spacing:0.14em;text-transform:uppercase;font-family:'Space Grotesk',sans-serif;">Inscriptions ouvertes</span>
            @endif
        </div>

        {{-- CTA buttons --}}
        <div style="display:flex;align-items:center;justify-content:center;gap:1rem;flex-wrap:wrap;">
            <a href="{{ route('public.event-detail', $event->slug) }}"
               style="display:inline-flex;align-items:center;gap:9px;background:#f59e0b;color:#000;font-size:0.72rem;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;padding:14px 28px;font-family:'Space Grotesk',sans-serif;transition:background .2s;"
               onmouseover="this.style.background='#fbbf24'" onmouseout="this.style.background='#f59e0b'">
                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Détails de l'événement
            </a>
            <a href="{{ route('public.athlete-list', $event->slug) }}"
               style="display:inline-flex;align-items:center;gap:9px;background:transparent;color:rgba(255,255,255,0.45);font-size:0.72rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;padding:14px 28px;border:1px solid rgba(255,255,255,0.1);font-family:'Space Grotesk',sans-serif;transition:all .2s;"
               onmouseover="this.style.color='#f59e0b';this.style.borderColor='rgba(245,158,11,0.4)'" onmouseout="this.style.color='rgba(255,255,255,0.45)';this.style.borderColor='rgba(255,255,255,0.1)'">
                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Liste des athlètes
            </a>
        </div>
    </div>
</div>

</div>

<style>
@keyframes pulseRing {
    0%   { transform: scale(0.4); opacity: 1; }
    100% { transform: scale(1.6); opacity: 0; }
}
@keyframes floatIcon {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-8px); }
}
@keyframes blink {
    0%,100% { opacity: 1; }
    50%      { opacity: 0.4; }
}
</style>

</x-public-layout>
