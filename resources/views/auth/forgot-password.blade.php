<!DOCTYPE html>
<html lang="fr" style="background:#000;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié — Ligue de Fatick</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { background: #000; color: #fff; font-family: 'Inter', system-ui, sans-serif; -webkit-font-smoothing: antialiased; margin: 0; padding: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; }

        @keyframes blob-pulse { 0%,100% { transform: scale(1); opacity: .6; } 50% { transform: scale(1.12); opacity: 1; } }
        .blob { position: absolute; border-radius: 9999px; filter: blur(80px); pointer-events: none; }
        .blob-1 { width: 480px; height: 480px; background: radial-gradient(circle, rgba(245,158,11,.09) 0%, transparent 70%); top: -80px; left: -120px; animation: blob-pulse 6s ease-in-out infinite; }
        .blob-2 { width: 320px; height: 320px; background: radial-gradient(circle, rgba(245,158,11,.06) 0%, transparent 70%); bottom: -60px; right: -80px; animation: blob-pulse 8s ease-in-out infinite 1.5s; }
        .grid-bg { position: absolute; inset: 0; opacity: .025; background-image: linear-gradient(rgba(245,158,11,1) 1px, transparent 1px), linear-gradient(90deg, rgba(245,158,11,1) 1px, transparent 1px); background-size: 48px 48px; pointer-events: none; }

        .card { position: relative; z-index: 10; width: 100%; max-width: 420px; padding: 0 24px; }

        .field-label { display: block; font-size: 0.78rem; font-weight: 600; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; }
        .field-input { width: 100%; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 0.9rem; padding: 11px 14px; border-radius: 9px; outline: none; transition: border-color .2s, background .2s; font-family: inherit; }
        .field-input::placeholder { color: rgba(255,255,255,0.2); }
        .field-input:focus { border-color: rgba(245,158,11,.6); background: rgba(245,158,11,.04); box-shadow: 0 0 0 3px rgba(245,158,11,.08); }

        .btn-submit { width: 100%; padding: 13px; background: #f59e0b; color: #000; font-weight: 800; font-size: 0.9rem; letter-spacing: 0.04em; border: none; border-radius: 9px; cursor: pointer; transition: background .2s; }
        .btn-submit:hover { background: #fbbf24; }
        .btn-submit:disabled { opacity: .6; cursor: not-allowed; }

        .alert-success { display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 9px; font-size: 0.85rem; margin-bottom: 20px; background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.25); color: #6ee7b7; }
        .alert-error   { display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 9px; font-size: 0.85rem; margin-bottom: 20px; background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.25); color: #fca5a5; }
    </style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="grid-bg"></div>

<div class="card">

    {{-- Logo --}}
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <img src="/images/logo.png" alt="Ligue de Fatick"
             style="width: 72px; height: 72px; object-fit: contain; margin: 0 auto 1.25rem; display: block;"
             onerror="this.style.display='none'">
        <h1 style="font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: -0.02em; margin: 0 0 6px; font-family: 'Space Grotesk', sans-serif;">Mot de passe oublié</h1>
        <p style="font-size: 0.82rem; color: rgba(255,255,255,.35); margin: 0; max-width: 320px; margin: 0 auto; line-height: 1.5;">Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
    </div>

    {{-- Card --}}
    <div style="background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.09); border-radius: 16px; padding: 32px; backdrop-filter: blur(20px);">

        {{-- Success --}}
        @if(session('status'))
        <div class="alert-success">
            <svg style="width:16px;height:16px;flex-shrink:0;margin-top:1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p style="margin:0;">{{ session('status') }}</p>
        </div>
        @endif

        {{-- Errors --}}
        @if($errors->any())
        <div class="alert-error">
            <svg style="width:16px;height:16px;flex-shrink:0;margin-top:1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div style="margin:0;">
                @foreach($errors->all() as $error)
                <p style="margin:0 0 2px;">{{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 18px;">
                <div>
                    <label class="field-label" for="email">Adresse e-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="email"
                           placeholder="votre@email.com"
                           class="field-input">
                </div>
                <button type="submit" class="btn-submit" :disabled="loading">
                    <span x-text="loading ? 'Envoi en cours…' : 'Envoyer le lien de réinitialisation'">Envoyer le lien de réinitialisation</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Back to login --}}
    <div style="margin-top: 1.5rem; text-align: center;">
        <a href="{{ route('login') }}"
           style="font-size: 0.82rem; color: rgba(255,255,255,.35); text-decoration: none; transition: color .2s; display: inline-flex; align-items: center; gap: 6px;"
           onmouseover="this.style.color='rgba(255,255,255,.7)'" onmouseout="this.style.color='rgba(255,255,255,.35)'">
            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour à la connexion
        </a>
        <p style="font-size: 0.72rem; color: rgba(255,255,255,.12); margin: 12px 0 0;">© {{ date('Y') }} Ligue de Fatick · Taekwondo</p>
    </div>

</div>
</body>
</html>
