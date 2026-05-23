<!DOCTYPE html>
<html lang="fr" style="background:#000;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion — Ligue de Fatick</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { background: #000; color: #fff; font-family: 'Inter', system-ui, sans-serif; -webkit-font-smoothing: antialiased; margin: 0; padding: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; }

        /* Animated blobs */
        @keyframes blob-pulse { 0%,100% { transform: scale(1); opacity: .6; } 50% { transform: scale(1.12); opacity: 1; } }
        .blob { position: absolute; border-radius: 9999px; filter: blur(80px); pointer-events: none; }
        .blob-1 { width: 480px; height: 480px; background: radial-gradient(circle, rgba(245,158,11,.09) 0%, transparent 70%); top: -80px; left: -120px; animation: blob-pulse 6s ease-in-out infinite; }
        .blob-2 { width: 320px; height: 320px; background: radial-gradient(circle, rgba(245,158,11,.06) 0%, transparent 70%); bottom: -60px; right: -80px; animation: blob-pulse 8s ease-in-out infinite 1.5s; }

        /* Grid */
        .grid-bg { position: absolute; inset: 0; opacity: .025; background-image: linear-gradient(rgba(245,158,11,1) 1px, transparent 1px), linear-gradient(90deg, rgba(245,158,11,1) 1px, transparent 1px); background-size: 48px 48px; pointer-events: none; }

        /* Card */
        .login-card { position: relative; z-index: 10; width: 100%; max-width: 400px; padding: 0 24px; }

        /* Inputs */
        .field-label { display: block; font-size: 0.78rem; font-weight: 600; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; }
        .field-input { width: 100%; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 0.9rem; padding: 11px 14px; border-radius: 9px; outline: none; transition: border-color .2s, background .2s; font-family: inherit; }
        .field-input::placeholder { color: rgba(255,255,255,0.2); }
        .field-input:focus { border-color: rgba(245,158,11,.6); background: rgba(245,158,11,.04); box-shadow: 0 0 0 3px rgba(245,158,11,.08); }
        .field-input.error { border-color: rgba(239,68,68,.5); }
        .field-input.error:focus { border-color: rgba(239,68,68,.7); box-shadow: 0 0 0 3px rgba(239,68,68,.08); }

        /* Submit button */
        .btn-login { width: 100%; padding: 13px; background: #f59e0b; color: #000; font-weight: 800; font-size: 0.9rem; letter-spacing: 0.04em; border: none; border-radius: 9px; cursor: pointer; transition: background .2s, transform .1s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-login:hover { background: #fbbf24; }
        .btn-login:active { transform: scale(.98); }
        .btn-login:disabled { opacity: .6; cursor: not-allowed; transform: none; }

        /* Checkbox */
        .custom-check { width: 16px; height: 16px; accent-color: #f59e0b; cursor: pointer; flex-shrink: 0; }

        /* Alerts */
        .alert { display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 9px; font-size: 0.85rem; margin-bottom: 20px; }
        .alert-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.25); color: #6ee7b7; }
        .alert-error   { background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.25); color: #fca5a5; }

        /* Spinner */
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner { animation: spin .7s linear infinite; }
        .spinner circle { stroke: #000; stroke-opacity: .25; }
        .spinner path { stroke: #000; }

        /* Password eye */
        .eye-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: rgba(255,255,255,.3); padding: 4px; transition: color .2s; }
        .eye-btn:hover { color: rgba(255,255,255,.7); }

        /* Divider */
        .divider { display: flex; align-items: center; gap: 12px; margin: 24px 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.07); }
        .divider span { color: rgba(255,255,255,.2); font-size: 0.75rem; }
    </style>
</head>
<body>

{{-- Background --}}
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="grid-bg"></div>

<div class="login-card">

    {{-- Logo --}}
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <img src="/images/logo.png" alt="Ligue de Fatick" style="width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1.25rem; display: block;" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex'">
        <div style="display: none; width: 68px; height: 68px; background: #f59e0b; border-radius: 18px; align-items: center; justify-content: center; margin: 0 auto 1.25rem; box-shadow: 0 0 40px rgba(245,158,11,.3);">
            <span style="font-size: 1.5rem; font-weight: 900; color: #000; font-family: 'Space Grotesk', sans-serif;">LRF</span>
        </div>
        <h1 style="font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: -0.02em; margin: 0 0 6px; font-family: 'Space Grotesk', sans-serif;">Ligue de Fatick</h1>
        <p style="font-size: 0.8rem; color: rgba(255,255,255,.35); margin: 0; letter-spacing: 0.04em;">Taekwondo · Portail Coach & Admin</p>
    </div>

    {{-- Card --}}
    <div style="background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.09); border-radius: 16px; padding: 32px; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">

        <h2 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 24px; letter-spacing: -0.02em;">Se connecter</h2>

        {{-- Flash success --}}
        @if(session('success'))
        <div class="alert alert-success">
            <svg style="width:16px;height:16px;flex-shrink:0;margin-top:1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p style="margin:0;">{{ session('success') }}</p>
        </div>
        @endif

        {{-- Errors --}}
        @if($errors->any())
        <div class="alert alert-error">
            <svg style="width:16px;height:16px;flex-shrink:0;margin-top:1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div style="margin:0;">
                @foreach($errors->all() as $error)
                <p style="margin:0 0 2px;">{{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" x-data="{ loading: false, showPass: false }" @submit="loading = true">
            @csrf

            <div style="display: flex; flex-direction: column; gap: 18px;">

                {{-- Email --}}
                <div>
                    <label class="field-label" for="email">Adresse e-mail</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="coach@lrftaekwondo.sn"
                        class="field-input {{ $errors->has('email') ? 'error' : '' }}">
                </div>

                {{-- Password --}}
                <div>
                    <label class="field-label" for="password">Mot de passe</label>
                    <div style="position: relative;">
                        <input
                            id="password"
                            :type="showPass ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            style="padding-right: 42px;"
                            class="field-input {{ $errors->has('password') ? 'error' : '' }}">
                        <button type="button" class="eye-btn" @click="showPass = !showPass" tabindex="-1">
                            <svg x-show="!showPass" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPass" style="width:16px;height:16px;display:none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember + forgot --}}
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" id="remember" name="remember" class="custom-check">
                        <label for="remember" style="font-size: 0.83rem; color: rgba(255,255,255,.4); cursor: pointer; user-select: none;">Se souvenir de moi</label>
                    </div>
                    <a href="{{ route('password.request') }}"
                       style="font-size: 0.8rem; color: rgba(245,158,11,.7); text-decoration: none; transition: color .2s;"
                       onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='rgba(245,158,11,.7)'">
                        Mot de passe oublié ?
                    </a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login" :disabled="loading">
                    <template x-if="loading">
                        <svg class="spinner" style="width:16px;height:16px;" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke-width="3"/>
                            <path d="M12 2a10 10 0 0110 10" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </template>
                    <span x-text="loading ? 'Connexion…' : 'Se connecter'">Se connecter</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Footer links --}}
    <div style="margin-top: 1.5rem; text-align: center;">
        <p style="font-size: 0.8rem; color: rgba(255,255,255,.25); margin: 0 0 8px;">
            <a href="{{ route('register') }}" style="color: #f59e0b; text-decoration: none; font-weight: 600; transition: opacity .2s;" onmouseover="this.style.opacity='.75'" onmouseout="this.style.opacity='1'">Créer un compte coach</a>
            <span style="margin: 0 10px; opacity: .2;">·</span>
            <a href="{{ route('public.home') }}" style="color: rgba(255,255,255,.35); text-decoration: none; transition: color .2s;" onmouseover="this.style.color='rgba(255,255,255,.7)'" onmouseout="this.style.color='rgba(255,255,255,.35)'">Site public</a>
        </p>
        <p style="font-size: 0.75rem; color: rgba(255,255,255,.15); margin: 0;">© {{ date('Y') }} Ligue de Fatick · Taekwondo</p>
    </div>

</div>

</body>
</html>
