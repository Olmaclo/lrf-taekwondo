@props(['title' => 'Sotaemad', 'breadcrumb' => null])
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — Ligue de Fatick</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="h-full flex overflow-hidden" x-data>

{{-- ── Sidebar ─────────────────────────────────────────────────────────────── --}}
<aside
    x-data="{ open: window.innerWidth >= 1024 }"
    @resize.window="open = window.innerWidth >= 1024"
    class="sidebar-el flex-shrink-0 flex flex-col transition-all duration-300 z-30"
    style="background: #080c12; border-right: 1px solid rgba(255,255,255,0.06);"
    :class="open ? 'w-64' : 'w-0 lg:w-16 overflow-hidden'"
>
    {{-- Header --}}
    <div class="flex items-center px-4 flex-shrink-0" style="height: 64px; border-bottom: 1px solid rgba(255,255,255,0.05); position: relative;">
        <div class="flex items-center gap-3 min-w-0">
            {{-- Logo --}}
            <img src="/images/logo.png" alt="LRF" class="flex-shrink-0" style="width: 36px; height: 36px; object-fit: contain;" onerror="this.style.display='none'">
            <div :class="open ? '' : 'lg:hidden'" class="min-w-0">
                <div style="font-family: 'Space Grotesk', sans-serif; font-weight: 700; color: #fff; font-size: 0.85rem; letter-spacing: 0.06em; line-height: 1;">Ligue de Fatick</div>
                <div style="font-size: 0.5rem; color: rgba(245,158,11,0.5); letter-spacing: 0.2em; text-transform: uppercase; margin-top: 2px;">Taekwondo · L.R.F</div>
            </div>
        </div>
        {{-- Gold accent line at top --}}
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, #f59e0b 0%, rgba(245,158,11,0.2) 100%);"></div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto py-5 px-2.5 space-y-0.5">
        @php $role = auth()->user()->roles->first()?->name ?? '' @endphp
        @php
            $dashRoute = match($role) {
                'technical' => 'technical.dashboard',
                'coach'     => 'coach.dashboard',
                'financial' => 'financial.dashboard',
                default     => 'dashboard',
            };
        @endphp

        <span class="nav-section" :class="open ? '' : 'lg:hidden'">Général</span>

        <a href="{{ route($dashRoute) }}" class="nav-item {{ request()->routeIs($dashRoute) ? 'active' : '' }}" title="Tableau de bord">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7zm11 0a2 2 0 012-2h1a2 2 0 012 2v3a2 2 0 01-2 2h-1a2 2 0 01-2-2V7zM3 16a2 2 0 012-2h3a2 2 0 012 2v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1zm11 0a2 2 0 012-2h1a2 2 0 012 2v1a2 2 0 01-2 2h-1a2 2 0 01-2-2v-1z"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Tableau de bord</span>
        </a>

        @if(auth()->user()->isTechnical() || auth()->user()->isAdmin())
        <span class="nav-section" :class="open ? '' : 'lg:hidden'">Gestion</span>
        <a href="{{ route('technical.dashboard') }}" class="nav-item {{ request()->routeIs('technical.dashboard') ? 'active' : '' }}" title="Athlètes & Draws">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Athlètes</span>
        </a>
        <a href="{{ route('coach.dashboard') }}" class="nav-item {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}" title="Coaches">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Coaches</span>
        </a>
        @endif

        @if(auth()->user()->isFinancial() || auth()->user()->isAdmin())
        <span class="nav-section" :class="open ? '' : 'lg:hidden'">Finance</span>
        <a href="{{ route('financial.dashboard') }}" class="nav-item {{ request()->routeIs('financial.dashboard') ? 'active' : '' }}" title="Finances">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Finances</span>
        </a>
        @endif

        @if(auth()->user()->isCoach())
        <span class="nav-section" :class="open ? '' : 'lg:hidden'">Mon espace</span>
        <a href="{{ route('coach.dashboard') }}" class="nav-item {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}" title="Mes athlètes">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Mes athlètes</span>
        </a>
        @endif

        {{-- Site public link --}}
        <div class="mt-auto pt-4">
            <a href="{{ route('public.home') }}" class="nav-item" title="Site public" target="_blank">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                <span :class="open ? '' : 'lg:hidden'">Site public</span>
            </a>
        </div>
    </nav>

    {{-- User area --}}
    <div style="border-top: 1px solid rgba(255,255,255,0.05); padding: 14px 12px; flex-shrink: 0; background: rgba(0,0,0,0.2);">
        <div class="flex items-center gap-3 min-w-0">
            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="flex-shrink-0 ring-1 ring-surface-700 object-cover"
                 style="width: 32px; height: 32px; border-radius: 50%;">
            <div class="min-w-0 flex-1" :class="open ? '' : 'lg:hidden'">
                <p class="text-sm font-semibold truncate" style="color: #e2e8f0; font-family: 'Space Grotesk', sans-serif;">{{ auth()->user()->name }}</p>
                <p class="text-xs capitalize truncate" style="color: rgba(255,255,255,0.3); letter-spacing: 0.04em;">{{ auth()->user()->roles->first()?->name ?? 'admin' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" :class="open ? '' : 'lg:hidden'" class="flex-shrink-0">
                @csrf
                <button type="submit" class="btn btn-ghost btn-icon p-1.5" title="Déconnexion" style="color: rgba(255,255,255,0.3);"
                        onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ── Main area ────────────────────────────────────────────────────────────── --}}
<div class="flex-1 flex flex-col min-w-0 overflow-hidden">

    {{-- Topbar --}}
    <header class="flex items-center px-5 gap-4 flex-shrink-0"
            style="height: 64px; background: rgba(8,12,18,0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.05);">
        <button
            @click="document.querySelector('.sidebar-el')._x_dataStack[0].open = !document.querySelector('.sidebar-el')._x_dataStack[0].open"
            class="btn btn-ghost btn-icon p-2 text-surface-400 lg:hidden">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="flex-1 min-w-0">
            <h1 class="font-semibold truncate" style="font-family: 'Space Grotesk', sans-serif; color: #f1f5f9; font-size: 0.95rem; letter-spacing: -0.01em;">{{ $title }}</h1>
            @if($breadcrumb)
            <p class="text-xs" style="color: rgba(255,255,255,0.3); letter-spacing: 0.04em; margin-top: 1px;">{{ $breadcrumb }}</p>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @stack('actions')
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-6">
        {{ $slot }}
    </main>
</div>

{{-- ── Toast container ──────────────────────────────────────────────────────── --}}
<div x-data class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none" style="min-width:300px;max-width:400px">
    <template x-for="toast in $store.toast.items" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            :class="{
                'toast-success': toast.type === 'success',
                'toast-error':   toast.type === 'error',
                'toast-info':    toast.type === 'info',
                'toast-warning': toast.type === 'warning',
            }"
            class="pointer-events-auto w-full"
        >
            <span x-text="toast.type === 'success' ? '✓' : toast.type === 'error' ? '✗' : toast.type === 'warning' ? '⚠' : 'ℹ'" class="flex-shrink-0 font-bold"></span>
            <span x-text="toast.message" class="flex-1 min-w-0"></span>
            <button @click="$store.toast.remove(toast.id)" class="flex-shrink-0 opacity-40 hover:opacity-100 transition-opacity">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>

@stack('scripts')
</body>
</html>
