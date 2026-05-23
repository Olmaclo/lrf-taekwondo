<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sotaemad' }} — Ligue de Taekwondo</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="h-full flex overflow-hidden" x-data>

{{-- ── Sidebar ────────────────────────────────────────────────────────────── --}}
<aside
    id="sidebar"
    x-data="{ open: window.innerWidth >= 1024 }"
    @resize.window="open = window.innerWidth >= 1024"
    class="flex-shrink-0 flex flex-col bg-surface-950 border-r border-surface-800 transition-all duration-300 z-30"
    :class="open ? 'w-64' : 'w-0 lg:w-16 overflow-hidden'"
>
    {{-- Logo --}}
    <div class="h-16 flex items-center px-4 border-b border-surface-800 flex-shrink-0">
        <div class="flex items-center gap-3 min-w-0">
            <img src="/images/logo.png" alt="LRF" class="w-9 h-9 object-contain flex-shrink-0" onerror="this.outerHTML='<div class=\'w-9 h-9 rounded-lg bg-brand-500 flex items-center justify-center flex-shrink-0 text-surface-900 font-black text-sm\'>LRF</div>'">
            <span class="font-black text-surface-50 tracking-tight truncate" :class="open ? '' : 'lg:hidden'">Ligue de Fatick</span>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-0.5">

        @php $role = auth()->user()->roles->first()?->name ?? '' @endphp

        {{-- Dashboard --}}
        <span class="nav-section" :class="open ? '' : 'lg:hidden'">Général</span>

        @php
            $dashRoute = match($role) {
                'technical' => 'technical.dashboard',
                'coach'     => 'coach.dashboard',
                'financial' => 'financial.dashboard',
                default     => 'dashboard',
            };
        @endphp

        <a href="{{ route($dashRoute) }}"
           class="nav-item {{ request()->routeIs($dashRoute) ? 'active' : '' }}"
           title="Tableau de bord">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7zm11 0a2 2 0 012-2h1a2 2 0 012 2v3a2 2 0 01-2 2h-1a2 2 0 01-2-2V7zM3 16a2 2 0 012-2h3a2 2 0 012 2v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1zm11 0a2 2 0 012-2h1a2 2 0 012 2v1a2 2 0 01-2 2h-1a2 2 0 01-2-2v-1z"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Tableau de bord</span>
        </a>

        @if(auth()->user()->isTechnical() || auth()->user()->isAdmin())
        <span class="nav-section" :class="open ? '' : 'lg:hidden'">Gestion</span>

        <a href="{{ route('technical.dashboard') }}"
           class="nav-item {{ request()->routeIs('technical.dashboard') ? 'active' : '' }}"
           title="Athlètes">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Athlètes</span>
        </a>

        <a href="{{ route('coach.dashboard') }}"
           class="nav-item {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}"
           title="Coaches">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Coaches</span>
        </a>

        <span class="nav-section" :class="open ? '' : 'lg:hidden'">Compétition</span>

        <a href="#events"
           class="nav-item"
           title="Événements"
           @click.prevent="$dispatch('open-events')">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Événements</span>
        </a>

        <a href="#draws"
           class="nav-item"
           title="Tirages"
           @click.prevent="$dispatch('open-draws')">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Tirages</span>
        </a>
        @endif

        @if(auth()->user()->isFinancial() || auth()->user()->isAdmin())
        <span class="nav-section" :class="open ? '' : 'lg:hidden'">Finance</span>
        <a href="{{ route('financial.dashboard') }}"
           class="nav-item {{ request()->routeIs('financial.dashboard') ? 'active' : '' }}"
           title="Finances">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Finances</span>
        </a>
        @endif

        @if(auth()->user()->isCoach())
        <span class="nav-section" :class="open ? '' : 'lg:hidden'">Mon espace</span>
        <a href="{{ route('coach.dashboard') }}"
           class="nav-item {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}"
           title="Mes athlètes">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span :class="open ? '' : 'lg:hidden'">Mes athlètes</span>
        </a>
        @endif
    </nav>

    {{-- User info --}}
    <div class="border-t border-surface-800 p-3 flex-shrink-0">
        <div class="flex items-center gap-3 min-w-0">
            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar"
                 class="w-8 h-8 rounded-full flex-shrink-0 ring-2 ring-surface-700">
            <div class="min-w-0 flex-1" :class="open ? '' : 'lg:hidden'">
                <p class="text-sm font-semibold text-surface-100 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-surface-500 truncate">{{ auth()->user()->roles->first()?->name ?? 'admin' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" :class="open ? '' : 'lg:hidden'">
                @csrf
                <button type="submit" class="btn-ghost btn-icon btn p-1.5" title="Déconnexion">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ── Main area ──────────────────────────────────────────────────────────── --}}
<div class="flex-1 flex flex-col min-w-0 overflow-hidden">

    {{-- Topbar --}}
    <header class="h-16 bg-surface-900/80 backdrop-blur-md border-b border-surface-800 flex items-center px-4 gap-4 flex-shrink-0">
        <button
            @click="document.getElementById('sidebar')._x_dataStack[0].open = !document.getElementById('sidebar')._x_dataStack[0].open"
            class="btn-ghost btn-icon btn p-2 text-surface-400 lg:hidden">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div class="flex-1 min-w-0">
            <h1 class="text-base font-semibold text-surface-100 truncate">{{ $title ?? 'Tableau de bord' }}</h1>
            @isset($breadcrumb)
            <p class="text-xs text-surface-500">{{ $breadcrumb }}</p>
            @endisset
        </div>

        <div class="flex items-center gap-2">
            @stack('actions')

            {{-- Profile menu --}}
            <div class="flex items-center gap-2">
                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full ring-2 ring-surface-700">
                <a href="{{ route('public.home') }}" class="btn btn-ghost btn-sm text-surface-400 hidden md:inline-flex" title="Voir le site public">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>
    </header>

    {{-- Page content --}}
    <main class="flex-1 overflow-y-auto p-6">
        {{ $slot }}
    </main>
</div>

{{-- ── Toast container ────────────────────────────────────────────────────── --}}
<div
    x-data
    class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 pointer-events-none"
    style="min-width:280px;max-width:380px">
    <template x-for="toast in $store.toast.items" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
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
            <span x-text="toast.type === 'success' ? '✓' : toast.type === 'error' ? '✗' : toast.type === 'warning' ? '⚠' : 'ℹ'" class="flex-shrink-0"></span>
            <span x-text="toast.message" class="flex-1 min-w-0"></span>
            <button @click="$store.toast.remove(toast.id)" class="flex-shrink-0 opacity-60 hover:opacity-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>

{{-- ── Confirm dialog (global) ────────────────────────────────────────────── --}}
<div
    x-data="confirmDialog()"
    x-show="open"
    @confirm-dialog.window="show($event.detail)"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="modal-backdrop"
    style="display:none">
    <div @click.stop class="modal max-w-md" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="modal-header">
            <h3 class="text-base font-bold text-surface-50" x-text="title"></h3>
            <button @click="cancel()" class="btn-ghost btn-icon btn p-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <p class="text-surface-300 text-sm" x-text="message"></p>
        </div>
        <div class="modal-footer">
            <button @click="cancel()" class="btn btn-secondary">Annuler</button>
            <button @click="confirm()" :class="danger ? 'btn btn-danger' : 'btn btn-primary'" x-text="confirmLabel"></button>
        </div>
    </div>
</div>

<script>
function confirmDialog() {
    return {
        open: false, title: '', message: '', confirmLabel: 'Confirmer', danger: false, _resolve: null,
        show({ title, message, confirmLabel = 'Confirmer', danger = false }) {
            this.title = title; this.message = message;
            this.confirmLabel = confirmLabel; this.danger = danger;
            this.open = true;
            return new Promise(r => this._resolve = r);
        },
        confirm() { this.open = false; this._resolve?.(true); },
        cancel()  { this.open = false; this._resolve?.(false); },
    };
}
// Global confirm helper
window.confirm2 = (opts) => {
    const detail = typeof opts === 'string' ? { title: 'Confirmer', message: opts } : opts;
    window.dispatchEvent(new CustomEvent('confirm-dialog', { detail }));
    return new Promise(resolve => {
        const handler = (e) => { resolve(e.detail); window.removeEventListener('confirm-dialog-result', handler); };
        window.addEventListener('confirm-dialog-result', handler);
    });
};
</script>

@stack('scripts')
</body>
</html>
