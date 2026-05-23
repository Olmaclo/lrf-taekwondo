@props(['title' => 'Ligue de Fatick', 'description' => 'Ligue Régionale de Taekwondo de Fatick'])
<!DOCTYPE html>
<html lang="fr" style="scroll-behavior: smooth; background: #000;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description }}">
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
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { background: #000; color: #fff; font-family: 'Inter', system-ui, sans-serif; -webkit-font-smoothing: antialiased; margin: 0; padding: 0; }
        h1, h2, h3, h4 { font-family: 'Space Grotesk', 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        /* ── Navbar ─────────────────────────────────────────────────────────── */
        #navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 50;
            transition: background 0.4s, box-shadow 0.4s;
        }
        #navbar .nav-inner {
            height: 80px; max-width: 1280px; margin: 0 auto; padding: 0 2.5rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        #navbar.scrolled {
            background: rgba(0,0,0,0.97);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 1px 0 rgba(245,158,11,0.15), 0 4px 32px rgba(0,0,0,0.5);
        }

        /* Nav links */
        .nav-link {
            color: rgba(255,255,255,0.38);
            font-size: 0.78rem; font-weight: 500; text-decoration: none;
            padding: 6px 14px; border-radius: 6px;
            transition: color 0.2s; position: relative; letter-spacing: 0.02em;
        }
        .nav-link::after {
            content: ''; position: absolute; bottom: -2px;
            left: 50%; right: 50%; height: 1px; background: #f59e0b;
            transition: left 0.25s ease, right 0.25s ease;
        }
        .nav-link:hover { color: #fff; }
        .nav-link:hover::after, .nav-link.active::after { left: 14px; right: 14px; }
        .nav-link.active { color: #fff; }

        /* CTA button */
        .nav-cta {
            display: inline-flex; align-items: center; gap: 8px;
            background: #f59e0b; color: #000;
            font-weight: 700; font-size: 0.75rem; letter-spacing: 0.06em; text-transform: uppercase;
            padding: 9px 22px; border-radius: 6px; text-decoration: none;
            transition: background 0.2s, box-shadow 0.2s;
            clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%);
        }
        .nav-cta:hover { background: #fbbf24; box-shadow: 0 0 24px rgba(245,158,11,0.35); }

        /* Mobile menu */
        #mobile-menu { display: none; background: #050505; border-bottom: 1px solid rgba(255,255,255,0.07); }
        #mobile-menu.open { display: block; }
        .mobile-nav-link {
            display: flex; align-items: center; gap: 14px;
            color: rgba(255,255,255,0.4); font-size: 0.875rem; font-weight: 500;
            text-decoration: none; padding: 15px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05); transition: color 0.15s;
        }
        .mobile-nav-link::before {
            content: ''; width: 18px; height: 1px; background: rgba(255,255,255,0.12);
            flex-shrink: 0; transition: background 0.15s, width 0.2s;
        }
        .mobile-nav-link:hover { color: #fff; }
        .mobile-nav-link:hover::before { background: #f59e0b; width: 28px; }
        .mobile-nav-link.active-m { color: #fff; }
        .mobile-nav-link.active-m::before { background: #f59e0b; }

        /* ── Footer ─────────────────────────────────────────────────────────── */
        #footer { background: #050505; position: relative; overflow: hidden; }
        #footer::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent 0%, rgba(245,158,11,0.5) 30%, rgba(245,158,11,0.5) 70%, transparent 100%);
        }
        .footer-title {
            color: rgba(255,255,255,0.18); font-size: 0.58rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.24em; margin-bottom: 1.5rem;
            display: flex; align-items: center; gap: 10px;
        }
        .footer-title::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.05); }
        .footer-link {
            color: rgba(255,255,255,0.28); font-size: 0.78rem; text-decoration: none;
            transition: color 0.2s; display: flex; align-items: center; gap: 0; padding: 5px 0;
            position: relative; overflow: hidden;
        }
        .footer-link::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 0; height: 1px; background: #f59e0b; transition: width 0.25s ease;
        }
        .footer-link:hover { color: #fff; padding-left: 18px; transition: color 0.2s, padding-left 0.25s; }
        .footer-link:hover::before { width: 14px; }

        /* ── Toast ─────────────────────────────────────────────────────────── */
        #flash-toast { position: fixed; bottom: 28px; right: 28px; z-index: 100; max-width: 380px; }
        @keyframes slideUpFade { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
        #flash-toast > div { animation: slideUpFade 0.35s cubic-bezier(0.22, 1, 0.36, 1); }
    </style>
</head>
<body>

{{-- ── NAVBAR ────────────────────────────────────────────────────────────────── --}}
<header id="navbar">
    <div class="nav-inner">

        {{-- Logo --}}
        <a href="{{ route('public.home') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none; flex-shrink: 0;">
            <img src="/images/logo.png" alt="Ligue de Fatick" style="width: 48px; height: 48px; object-fit: contain; flex-shrink: 0;" onerror="this.style.display='none'">
            <div>
                <div style="font-weight: 700; color: #fff; font-size: 0.9rem; letter-spacing: 0.06em; line-height: 1; font-family: 'Space Grotesk', sans-serif;">Ligue de Fatick</div>
                <div style="font-size: 0.52rem; color: rgba(245,158,11,0.55); letter-spacing: 0.2em; text-transform: uppercase; margin-top: 3px; font-family: 'Space Grotesk', sans-serif;">Taekwondo · L.R.F</div>
            </div>
        </a>

        {{-- Desktop nav --}}
        @php
            $navLinks = [
                ['route' => 'public.home',    'label' => 'Accueil'],
                ['route' => 'public.events',  'label' => 'Événements'],
                ['route' => 'public.gallery', 'label' => 'Galerie'],
                ['route' => 'public.blog',    'label' => 'Actualités'],
                ['route' => 'public.verify',  'label' => 'Mon inscription'],
                ['route' => 'public.contact', 'label' => 'Contact'],
            ];
        @endphp
        <nav style="display: none; align-items: center; gap: 4px;" id="desktop-nav">
            @foreach($navLinks as $link)
            <a href="{{ route($link['route']) }}"
               class="nav-link {{ request()->routeIs($link['route']) ? 'active' : '' }}">
                {{ $link['label'] }}
            </a>
            @endforeach
        </nav>

        {{-- Actions --}}
        <div style="display: flex; align-items: center; gap: 20px; flex-shrink: 0;" id="desktop-actions">
            @auth
            <a href="{{ route('dashboard') }}" class="nav-link">Tableau de bord</a>
            @else
            <a href="{{ route('login') }}" class="nav-link">Connexion</a>
            @endauth
            <a href="{{ route('register') }}" class="nav-cta">
                S'inscrire
                <svg style="width: 11px; height: 11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        {{-- Hamburger --}}
        <button id="hamburger" onclick="toggleMenu()"
                style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; cursor: pointer; color: rgba(255,255,255,0.55); padding: 0; transition: all 0.2s;"
                onmouseover="this.style.background='rgba(255,255,255,0.08)'; this.style.color='#fff'"
                onmouseout="this.style.background='rgba(255,255,255,0.04)'; this.style.color='rgba(255,255,255,0.55)'">
            <svg id="icon-open" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
            <svg id="icon-close" style="width: 18px; height: 18px; display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem 1.5rem;">
            @foreach($navLinks as $link)
            <a href="{{ route($link['route']) }}"
               class="mobile-nav-link {{ request()->routeIs($link['route']) ? 'active-m' : '' }}">
                {{ $link['label'] }}
            </a>
            @endforeach
            <div style="padding-top: 1.25rem; display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('register') }}" class="nav-cta" style="justify-content: center; clip-path: none; border-radius: 8px;">
                    S'inscrire →
                </a>
                @auth
                <a href="{{ route('dashboard') }}" style="color: rgba(255,255,255,0.3); text-align: center; font-size: 0.78rem; text-decoration: none; padding: 10px 0; letter-spacing: 0.06em; text-transform: uppercase;">Tableau de bord</a>
                @else
                <a href="{{ route('login') }}" style="color: rgba(255,255,255,0.3); text-align: center; font-size: 0.78rem; text-decoration: none; padding: 10px 0; letter-spacing: 0.06em; text-transform: uppercase;">Connexion</a>
                @endauth
            </div>
        </div>
    </div>
</header>

{{-- ── Flash ──────────────────────────────────────────────────────────────────── --}}
@if(session('success'))
<div id="flash-toast">
    <div style="display: flex; align-items: flex-start; gap: 14px; background: #fff; color: #000; padding: 18px 20px 18px 16px; border-radius: 10px; box-shadow: 0 24px 64px rgba(0,0,0,0.6); font-size: 0.875rem; font-weight: 500; border-left: 3px solid #f59e0b;">
        <svg style="width: 18px; height: 18px; color: #f59e0b; flex-shrink: 0; margin-top: 1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span style="flex: 1; line-height: 1.5;">{{ session('success') }}</span>
        <button onclick="document.getElementById('flash-toast').remove()"
                style="background: none; border: none; cursor: pointer; color: rgba(0,0,0,0.25); padding: 0; line-height: 1; transition: color 0.15s; flex-shrink: 0;"
                onmouseover="this.style.color='#000'" onmouseout="this.style.color='rgba(0,0,0,0.25)'">
            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>
<script>setTimeout(function(){ var t = document.getElementById('flash-toast'); if(t) t.remove(); }, 5000);</script>
@endif

@if(session('inscription_error'))
<div id="flash-error-toast" style="position: fixed; top: 100px; right: 24px; z-index: 9999; max-width: 420px; animation: slideIn 0.3s ease;">
    <div style="display: flex; align-items: flex-start; gap: 14px; background: #1a0a0a; color: #fff; padding: 18px 20px 18px 16px; border-radius: 10px; box-shadow: 0 24px 64px rgba(0,0,0,0.7); font-size: 0.875rem; font-weight: 500; border-left: 3px solid #ef4444;">
        <svg style="width: 18px; height: 18px; color: #ef4444; flex-shrink: 0; margin-top: 1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span style="flex: 1; line-height: 1.5; color: rgba(255,255,255,0.85);">{{ session('inscription_error') }}</span>
        <button onclick="document.getElementById('flash-error-toast').remove()"
                style="background: none; border: none; cursor: pointer; color: rgba(255,255,255,0.25); padding: 0; line-height: 1; flex-shrink: 0;"
                onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">
            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>
<script>setTimeout(function(){ var t = document.getElementById('flash-error-toast'); if(t) t.remove(); }, 7000);</script>
@endif

{{-- ── Page content ────────────────────────────────────────────────────────────── --}}
{{ $slot }}

{{-- ── FOOTER ────────────────────────────────────────────────────────────────── --}}
<footer id="footer">

    {{-- Ghost background text --}}
    <div aria-hidden style="position: absolute; bottom: 1.5rem; right: -1rem; font-size: clamp(4rem, 11vw, 9rem); font-weight: 700; color: rgba(255,255,255,0.014); line-height: 1; letter-spacing: -0.04em; white-space: nowrap; pointer-events: none; user-select: none; font-family: 'Space Grotesk', sans-serif;">
        L.R.F
    </div>

    <div style="max-width: 1280px; margin: 0 auto; padding: 5.5rem 2.5rem 4rem; position: relative; z-index: 1;">
        <div style="display: grid; grid-template-columns: 1.6fr 1fr 1fr 1fr 1.1fr; gap: 3rem; flex-wrap: wrap;">

            {{-- Brand --}}
            <div>
                <a href="{{ route('public.home') }}" style="display: inline-flex; align-items: center; gap: 12px; text-decoration: none; margin-bottom: 1.75rem;">
                    <img src="/images/logo.png" alt="Ligue de Fatick" style="width: 52px; height: 52px; object-fit: contain; flex-shrink: 0;" onerror="this.style.display='none'">
                    <div>
                        <div style="font-weight: 700; color: #fff; font-size: 0.95rem; letter-spacing: 0.06em; font-family: 'Space Grotesk', sans-serif;">Ligue de Fatick</div>
                        <div style="font-size: 0.55rem; color: rgba(245,158,11,0.55); letter-spacing: 0.2em; text-transform: uppercase; margin-top: 3px; font-family: 'Space Grotesk', sans-serif;">Taekwondo · L.R.F</div>
                    </div>
                </a>
                <p style="color: rgba(255,255,255,0.25); font-size: 0.78rem; line-height: 1.85; max-width: 22rem; margin-bottom: 2rem;">
                    Organisation nationale dédiée au développement et à la promotion du Taekwondo au Sénégal. Excellence, discipline, fair-play.
                </p>
                <div style="display: flex; gap: 8px;">
                    @foreach([
                        ['label' => 'Facebook',  'path' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
                        ['label' => 'Instagram', 'path' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z'],
                    ] as $s)
                    <span aria-label="{{ $s['label'] }}" title="Bientôt disponible"
                       style="width: 36px; height: 36px; border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.12); cursor: default;">
                        <svg style="width: 14px; height: 14px;" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $s['path'] }}"/></svg>
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Nav --}}
            <div>
                <div class="footer-title">Navigation</div>
                @foreach([
                    ['route' => 'public.home',        'label' => 'Accueil'],
                    ['route' => 'public.events',      'label' => 'Événements'],
                    ['route' => 'public.gallery',     'label' => 'Galerie photo'],
                    ['route' => 'public.blog',        'label' => 'Actualités'],
                    ['route' => 'public.verify',      'label' => 'Mon inscription'],
                    ['route' => 'public.contact',     'label' => 'Contact'],
                ] as $link)
                <a href="{{ route($link['route']) }}" class="footer-link">{{ $link['label'] }}</a>
                @endforeach
            </div>

            {{-- Espace coach --}}
            <div>
                <div class="footer-title">Espace coach</div>
                <a href="{{ route('login') }}" class="footer-link">Se connecter</a>
                <a href="{{ route('register') }}" class="footer-link">Créer un compte</a>
                <a href="{{ route('public.inscription') }}" class="footer-link">Inscrire un athlète</a>
            </div>

            {{-- Légal --}}
            <div>
                <div class="footer-title">Légal</div>
                <a href="{{ route('public.privacy') }}" class="footer-link">Confidentialité</a>
                <a href="{{ route('public.terms') }}" class="footer-link">Conditions d'utilisation</a>
                <a href="{{ route('public.data-compliance') }}" class="footer-link">Conformité des données</a>
                <a href="{{ route('public.intellectual-property') }}" class="footer-link">Propriété intellectuelle</a>
            </div>

            {{-- Contact --}}
            <div>
                <div class="footer-title">Contact</div>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    {{-- Localisation --}}
                    <div style="display: flex; align-items: flex-start; gap: 10px; font-size: 0.77rem; color: rgba(255,255,255,0.28);">
                        <svg style="width: 13px; height: 13px; color: #f59e0b; flex-shrink: 0; margin-top: 2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        <span>Fatick, Sénégal</span>
                    </div>
                    {{-- Email --}}
                    <a href="mailto:contact@lrftaekwondo.com" style="display: flex; align-items: flex-start; gap: 10px; font-size: 0.77rem; color: rgba(255,255,255,0.28); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='rgba(255,255,255,0.28)'">
                        <svg style="width: 13px; height: 13px; color: #f59e0b; flex-shrink: 0; margin-top: 2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        <span>contact@lrftaekwondo.com</span>
                    </a>
                    {{-- Téléphone --}}
                    <a href="tel:+221773056998" style="display: flex; align-items: flex-start; gap: 10px; font-size: 0.77rem; color: rgba(255,255,255,0.28); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='rgba(255,255,255,0.28)'">
                        <svg style="width: 13px; height: 13px; color: #f59e0b; flex-shrink: 0; margin-top: 2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        <span>77 305 69 98</span>
                    </a>
                    {{-- Lien page contact --}}
                    <a href="{{ route('public.contact') }}" style="display: inline-flex; align-items: center; gap: 7px; margin-top: 6px; font-size: 0.72rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #f59e0b; text-decoration: none; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                        Nous contacter
                        <svg style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Bottom bar --}}
    <div style="border-top: 1px solid rgba(255,255,255,0.04); max-width: 1280px; margin: 0 auto; padding: 1.25rem 2.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; position: relative; z-index: 1;">
        <p style="color: rgba(255,255,255,0.12); font-size: 0.68rem; letter-spacing: 0.06em;">
            &copy; {{ date('Y') }} Ligue Régionale de Fatick — Taekwondo · Tous droits réservés
        </p>
        <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
            <a href="{{ route('public.privacy') }}" style="color: rgba(255,255,255,0.12); font-size: 0.68rem; text-decoration: none; transition: color 0.2s; letter-spacing: 0.06em;" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='rgba(255,255,255,0.12)'">Confidentialité</a>
            <a href="{{ route('public.terms') }}" style="color: rgba(255,255,255,0.12); font-size: 0.68rem; text-decoration: none; transition: color 0.2s; letter-spacing: 0.06em;" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='rgba(255,255,255,0.12)'">CGU</a>
            <a href="{{ route('public.data-compliance') }}" style="color: rgba(255,255,255,0.12); font-size: 0.68rem; text-decoration: none; transition: color 0.2s; letter-spacing: 0.06em;" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='rgba(255,255,255,0.12)'">Données</a>
            <a href="{{ route('public.intellectual-property') }}" style="color: rgba(255,255,255,0.12); font-size: 0.68rem; text-decoration: none; transition: color 0.2s; letter-spacing: 0.06em;" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='rgba(255,255,255,0.12)'">Propriété intellectuelle</a>
        </div>
    </div>
</footer>

<script>
window.addEventListener('scroll', function() {
    document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 40);
});

function toggleMenu() {
    var menu = document.getElementById('mobile-menu');
    var iconOpen = document.getElementById('icon-open');
    var iconClose = document.getElementById('icon-close');
    var isOpen = menu.classList.toggle('open');
    iconOpen.style.display = isOpen ? 'none' : 'block';
    iconClose.style.display = isOpen ? 'block' : 'none';
}

function checkNavVisibility() {
    var w = window.innerWidth;
    document.getElementById('desktop-nav').style.display = w >= 768 ? 'flex' : 'none';
    document.getElementById('desktop-actions').style.display = w >= 768 ? 'flex' : 'none';
    document.getElementById('hamburger').style.display = w >= 768 ? 'none' : 'flex';
}
checkNavVisibility();
window.addEventListener('resize', checkNavVisibility);
</script>

</body>
</html>
