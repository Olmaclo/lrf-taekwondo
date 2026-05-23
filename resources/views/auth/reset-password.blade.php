<!DOCTYPE html>
<html lang="fr" style="background:#000;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe — Ligue de Fatick</title>
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
        .field-input.error { border-color: rgba(239,68,68,.5); }

        .btn-submit { width: 100%; padding: 13px; background: #f59e0b; color: #000; font-weight: 800; font-size: 0.9rem; letter-spacing: 0.04em; border: none; border-radius: 9px; cursor: pointer; transition: background .2s; }
        .btn-submit:hover { background: #fbbf24; }
        .btn-submit:disabled { opacity: .6; cursor: not-allowed; }

        .alert-error { display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 9px; font-size: 0.85rem; margin-bottom: 20px; background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.25); color: #fca5a5; }

        .eye-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: rgba(255,255,255,.3); padding: 4px; transition: color .2s; }
        .eye-btn:hover { color: rgba(255,255,255,.7); }

        .strength-bar { height: 3px; border-radius: 2px; margin-top: 6px; transition: width .3s, background .3s; }
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
        <h1 style="font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: -0.02em; margin: 0 0 6px; font-family: 'Space Grotesk', sans-serif;">Nouveau mot de passe</h1>
        <p style="font-size: 0.82rem; color: rgba(255,255,255,.35); margin: 0;">Choisissez un mot de passe sécurisé pour votre compte.</p>
    </div>

    {{-- Card --}}
    <div style="background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.09); border-radius: 16px; padding: 32px; backdrop-filter: blur(20px);">

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

        <form method="POST" action="{{ route('password.update') }}"
              x-data="{ loading: false, showPwd: false, showConfirm: false, strength: 0 }"
              @submit="loading = true">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div style="display: flex; flex-direction: column; gap: 18px;">

                {{-- Email --}}
                <div>
                    <label class="field-label" for="email">Adresse e-mail</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email', $request->email) }}"
                           required autocomplete="email"
                           placeholder="votre@email.com"
                           class="field-input {{ $errors->has('email') ? 'error' : '' }}">
                </div>

                {{-- New password --}}
                <div>
                    <label class="field-label" for="password">Nouveau mot de passe</label>
                    <div style="position: relative;">
                        <input id="password" :type="showPwd ? 'text' : 'password'" name="password"
                               required autocomplete="new-password"
                               placeholder="Minimum 8 caractères"
                               style="padding-right: 42px;"
                               class="field-input {{ $errors->has('password') ? 'error' : '' }}"
                               @input="strength = $event.target.value.length >= 12 ? 3 : ($event.target.value.length >= 8 ? 2 : ($event.target.value.length >= 4 ? 1 : 0))">
                        <button type="button" class="eye-btn" @click="showPwd = !showPwd" tabindex="-1">
                            <svg x-show="!showPwd" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPwd" style="width:16px;height:16px;display:none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    {{-- Strength indicator --}}
                    <div style="display: flex; gap: 4px; margin-top: 6px;" x-show="strength > 0">
                        <div class="strength-bar" style="flex:1;" :style="strength >= 1 ? 'background:' + (strength === 1 ? '#ef4444' : strength === 2 ? '#f59e0b' : '#22c55e') : 'background:rgba(255,255,255,.08)'"></div>
                        <div class="strength-bar" style="flex:1;" :style="strength >= 2 ? 'background:' + (strength === 2 ? '#f59e0b' : '#22c55e') : 'background:rgba(255,255,255,.08)'"></div>
                        <div class="strength-bar" style="flex:1;" :style="strength >= 3 ? 'background:#22c55e' : 'background:rgba(255,255,255,.08)'"></div>
                    </div>
                    <p x-show="strength > 0" style="font-size:0.72rem; margin: 4px 0 0; color: rgba(255,255,255,.3);"
                       x-text="strength === 1 ? 'Faible' : strength === 2 ? 'Moyen' : 'Fort'"></p>
                </div>

                {{-- Confirm password --}}
                <div>
                    <label class="field-label" for="password_confirmation">Confirmer le mot de passe</label>
                    <div style="position: relative;">
                        <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                               name="password_confirmation"
                               required autocomplete="new-password"
                               placeholder="Répétez le mot de passe"
                               style="padding-right: 42px;"
                               class="field-input">
                        <button type="button" class="eye-btn" @click="showConfirm = !showConfirm" tabindex="-1">
                            <svg x-show="!showConfirm" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showConfirm" style="width:16px;height:16px;display:none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit" :disabled="loading">
                    <span x-text="loading ? 'Réinitialisation…' : 'Réinitialiser mon mot de passe'">Réinitialiser mon mot de passe</span>
                </button>
            </div>
        </form>
    </div>

    <div style="margin-top: 1.5rem; text-align: center;">
        <p style="font-size: 0.72rem; color: rgba(255,255,255,.12); margin: 0;">© {{ date('Y') }} Ligue de Fatick · Taekwondo</p>
    </div>

</div>
</body>
</html>
