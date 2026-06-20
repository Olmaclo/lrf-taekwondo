<!DOCTYPE html>
<html lang="fr" style="background:#000;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Créer un compte coach — Ligue de Fatick</title>
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
        html, body { background: #000; color: #fff; font-family: 'Inter', system-ui, sans-serif; -webkit-font-smoothing: antialiased; margin: 0; padding: 0; min-height: 100vh; }

        @keyframes blob-pulse { 0%,100% { transform: scale(1); opacity: .5; } 50% { transform: scale(1.1); opacity: .9; } }
        .blob { position: absolute; border-radius: 9999px; filter: blur(90px); pointer-events: none; }
        .blob-1 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(245,158,11,.07) 0%, transparent 70%); top: -100px; left: -100px; animation: blob-pulse 7s ease-in-out infinite; }
        .blob-2 { width: 340px; height: 340px; background: radial-gradient(circle, rgba(245,158,11,.05) 0%, transparent 70%); bottom: -80px; right: -60px; animation: blob-pulse 9s ease-in-out infinite 2s; }
        .grid-bg { position: absolute; inset: 0; opacity: .02; background-image: linear-gradient(rgba(245,158,11,1) 1px, transparent 1px), linear-gradient(90deg, rgba(245,158,11,1) 1px, transparent 1px); background-size: 52px 52px; pointer-events: none; }

        .field-label { display: block; font-size: 0.65rem; font-weight: 700; color: rgba(255,255,255,0.32); text-transform: uppercase; letter-spacing: 0.14em; margin-bottom: 7px; font-family: 'Space Grotesk', sans-serif; }
        .field-input { width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); color: #fff; font-size: 0.875rem; padding: 11px 14px; border-radius: 8px; outline: none; transition: border-color .2s, background .2s, box-shadow .2s; font-family: inherit; box-sizing: border-box; }
        .field-input::placeholder { color: rgba(255,255,255,0.18); }
        .field-input:focus { border-color: rgba(245,158,11,.5); background: rgba(245,158,11,.03); box-shadow: 0 0 0 3px rgba(245,158,11,.07); }
        .section-divider { display: flex; align-items: center; gap: 12px; margin: 4px 0; }
        .section-divider-line { flex: 1; height: 1px; background: rgba(255,255,255,0.05); }
        .section-divider-label { color: rgba(255,255,255,0.15); font-size: 0.62rem; letter-spacing: 0.14em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body style="position: relative; overflow-x: hidden; display: flex; align-items: flex-start; justify-content: center; padding: 80px 1.5rem 5rem;">

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="grid-bg"></div>

<div style="width: 100%; max-width: 520px; position: relative; z-index: 10;">

    {{-- Logo --}}
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <a href="{{ route('public.home') }}" style="display: inline-flex; flex-direction: column; align-items: center; gap: 14px; text-decoration: none;">
            <img src="/images/logo.png" alt="Ligue de Fatick"
                 style="width: 72px; height: 72px; object-fit: contain; filter: drop-shadow(0 0 24px rgba(245,158,11,0.25));"
                 onerror="this.style.display='none'">
            <div>
                <div style="font-weight: 700; color: #fff; font-size: 1rem; letter-spacing: 0.06em; font-family: 'Space Grotesk', sans-serif;">Ligue de Fatick</div>
                <div style="font-size: 0.58rem; color: rgba(245,158,11,0.55); letter-spacing: 0.22em; text-transform: uppercase; margin-top: 3px; font-family: 'Space Grotesk', sans-serif;">Taekwondo · L.R.F</div>
            </div>
        </a>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #fff; letter-spacing: -0.03em; margin: 1.5rem 0 5px; font-family: 'Space Grotesk', sans-serif;">Créer un compte coach</h1>
        <p style="color: rgba(255,255,255,0.28); font-size: 0.82rem; margin: 0; letter-spacing: 0.02em;">Remplissez vos informations pour rejoindre la Ligue</p>
    </div>

    {{-- Errors --}}
    @if($errors->any())
    <div style="background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.18); border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.5rem;">
        @foreach($errors->all() as $error)
        <p style="color: #fca5a5; font-size: 0.78rem; margin: 0 0 3px; display: flex; align-items: baseline; gap: 7px;">
            <span style="color: rgba(252,165,165,0.4); font-size: 0.7rem;">—</span> {{ $error }}
        </p>
        @endforeach
    </div>
    @endif

    {{-- Form card --}}
    <form method="POST" action="{{ route('register') }}"
          style="background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 2rem; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); position: relative; overflow: hidden;">

        <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(245,158,11,0.4), transparent);"></div>

        @csrf

        <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 1.5rem;">

            {{-- Section : Identité --}}
            <div class="section-divider">
                <div class="section-divider-line"></div>
                <span class="section-divider-label">Identité</span>
                <div class="section-divider-line"></div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label class="field-label">Prénom <span style="color:#f59e0b;">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                           placeholder="Moussa" required autocomplete="given-name" class="field-input">
                </div>
                <div>
                    <label class="field-label">Nom <span style="color:#f59e0b;">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                           placeholder="DIALLO" required autocomplete="family-name" class="field-input">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label class="field-label">Date de naissance <span style="color:#f59e0b;">*</span></label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                           required class="field-input">
                </div>
                <div>
                    <label class="field-label">Lieu de naissance <span style="color:#f59e0b;">*</span></label>
                    <input type="text" name="birth_place" value="{{ old('birth_place') }}"
                           placeholder="Fatick" required class="field-input">
                </div>
            </div>

            {{-- Section : Club --}}
            <div class="section-divider" style="margin-top:4px;">
                <div class="section-divider-line"></div>
                <span class="section-divider-label">Club</span>
                <div class="section-divider-line"></div>
            </div>

            <div>
                <label class="field-label">Nom du club <span style="color:#f59e0b;">*</span></label>
                <input type="text" name="club" value="{{ old('club') }}"
                       placeholder="Club Taekwondo de Fatick" required autocomplete="organization" class="field-input">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label class="field-label">N° de licence <span style="color:#f59e0b;">*</span></label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}"
                           placeholder="LIC-2024-001" required class="field-input">
                </div>
                <div>
                    <label class="field-label">Code fédéral du club <span style="color:#f59e0b;">*</span></label>
                    <input type="text" name="federal_code" value="{{ old('federal_code') }}"
                           placeholder="Ex : 221001" required inputmode="numeric"
                           pattern="[0-9]+" title="Chiffres uniquement" class="field-input">
                </div>
            </div>

            {{-- Section : Connexion --}}
            <div class="section-divider" style="margin-top:4px;">
                <div class="section-divider-line"></div>
                <span class="section-divider-label">Connexion</span>
                <div class="section-divider-line"></div>
            </div>

            <div>
                <label class="field-label">Adresse email <span style="color:#f59e0b;">*</span></label>
                <div style="position: relative;">
                    <input type="email" id="email-field" name="email" value="{{ old('email') }}"
                           placeholder="vous@exemple.com" required autocomplete="email" class="field-input"
                           style="padding-right: 44px;"
                           oninput="validateEmailLive(this.value)" onblur="validateEmailLive(this.value)">
                    <span id="email-indicator" style="position: absolute; right: 13px; top: 50%; transform: translateY(-50%); display: none;"></span>
                </div>
                <p id="email-feedback" style="margin: 5px 0 0; font-size: 0.72rem; min-height: 16px; display: none;"></p>
            </div>

            <div>
                <label class="field-label">Mot de passe <span style="color:#f59e0b;">*</span></label>
                <div style="position: relative;">
                    <input type="password" id="pwd-field" name="password"
                           placeholder="Minimum 10 caractères, lettres et chiffres" required autocomplete="new-password"
                           class="field-input" style="padding-right: 44px;">
                    <button type="button" onclick="togglePwd('pwd-field')"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: rgba(255,255,255,0.25); padding: 4px; transition: color 0.2s; line-height: 1;"
                            onmouseover="this.style.color='rgba(255,255,255,0.6)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">
                        <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="field-label">Confirmer le mot de passe <span style="color:#f59e0b;">*</span></label>
                <input type="password" name="password_confirmation"
                       placeholder="Répétez le mot de passe" required autocomplete="new-password" class="field-input">
            </div>

        </div>

        {{-- Notice --}}
        <div style="background: rgba(245,158,11,0.04); border: 1px solid rgba(245,158,11,0.1); border-radius: 8px; padding: 12px 14px; margin-bottom: 1.5rem; display: flex; gap: 10px; align-items: flex-start;">
            <svg style="width: 14px; height: 14px; color: #f59e0b; flex-shrink: 0; margin-top: 1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
            <p style="color: rgba(255,255,255,0.28); font-size: 0.76rem; line-height: 1.55; margin: 0;">Votre compte sera activé après vérification de vos informations par l'équipe technique de la Ligue. Vous pourrez ensuite inscrire vos athlètes aux compétitions.</p>
        </div>

        <div id="register-error" style="display:none;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:10px 14px;margin-bottom:14px;color:#fca5a5;font-size:0.8rem;"></div>
        <button type="submit" id="register-submit"
                style="width: 100%; padding: 13px; background: #f59e0b; color: #000; font-weight: 700; font-size: 0.8rem; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s, box-shadow 0.2s; letter-spacing: 0.07em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif; clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%);"
                onmouseover="if(!this.disabled){this.style.background='#fbbf24'; this.style.boxShadow='0 0 24px rgba(245,158,11,0.3)'}"
                onmouseout="if(!this.disabled){this.style.background='#f59e0b'; this.style.boxShadow='none'}">
            Créer mon compte
        </button>

        <p style="text-align: center; color: rgba(255,255,255,0.22); font-size: 0.78rem; margin: 1.25rem 0 0;">
            Déjà un compte ?
            <a href="{{ route('login') }}" style="color: #f59e0b; text-decoration: none; font-weight: 600; transition: opacity .2s;"
               onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
                Se connecter
            </a>
        </p>

        {{-- Honeypot — invisible pour les humains, rempli par les bots --}}
        <div aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;" tabindex="-1">
            <label for="website_url">Ne pas remplir</label>
            <input type="text" id="website_url" name="website_url" autocomplete="off" tabindex="-1">
        </div>
    </form>

    <p style="text-align: center; color: rgba(255,255,255,0.08); font-size: 0.65rem; margin-top: 1.5rem; letter-spacing: 0.06em;">&copy; {{ date('Y') }} Ligue de Fatick · Taekwondo</p>
</div>

<script>
function togglePwd(id) {
    var input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

document.querySelector('form').addEventListener('submit', function(e) {
    var errEl = document.getElementById('register-error');
    var pwd   = document.getElementById('pwd-field').value;
    var conf  = document.querySelector('[name=password_confirmation]').value;
    var msgs  = [];

    if (pwd.length < 10) {
        msgs.push('Le mot de passe doit contenir au moins 10 caractères.');
    } else if (pwd !== conf) {
        msgs.push('Les mots de passe ne correspondent pas.');
    }

    if (msgs.length > 0) {
        e.preventDefault();
        errEl.innerHTML = msgs.map(function(m) { return '• ' + m; }).join('<br>');
        errEl.style.display = 'block';
        errEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    errEl.style.display = 'none';
    var btn = document.getElementById('register-submit');
    btn.disabled = true;
    btn.style.opacity = '0.65';
    btn.style.cursor = 'not-allowed';
    btn.textContent = 'Création en cours…';
});

// ── Email validation ──────────────────────────────────────────────────────────
var DISPOSABLE_DOMAINS = [
    'mailinator.com','mailinator.net','mailinator.org',
    'guerrillamail.com','guerrillamail.info','guerrillamail.biz',
    'guerrillamail.de','guerrillamail.net','guerrillamail.org',
    'guerrillamailblock.com','grr.la','sharklasers.com','spam4.me',
    '10minutemail.com','10minutemail.net','10minutemail.org',
    'tempmail.com','temp-mail.org','temp-mail.io','tempmail.net',
    'throwaway.email','throwam.com',
    'yopmail.com','yopmail.fr','yopmail.net',
    'trashmail.com','trashmail.at','trashmail.io','trashmail.me',
    'trashmail.net','trashmail.org','trashmail.xyz',
    'dispostable.com','discard.email',
    'maildrop.cc','mailnull.com','mailnesia.com','mailtemp.info',
    'getairmail.com','fakeinbox.com',
    'spamgourmet.com','moakt.com','mohmal.com','mytrashmail.com',
    'crazymailing.com','firemailbox.club','tempr.email',
    'tmpmail.net','tmpmail.org','tmpeml.com',
];

var emailTimer = null;

function validateEmailLive(value) {
    clearTimeout(emailTimer);
    emailTimer = setTimeout(function() { _checkEmail(value); }, 350);
}

function _checkEmail(value) {
    var field      = document.getElementById('email-field');
    var indicator  = document.getElementById('email-indicator');
    var feedback   = document.getElementById('email-feedback');

    if (!value || value.length < 5) {
        field.style.borderColor = '';
        indicator.style.display = 'none';
        feedback.style.display  = 'none';
        return;
    }

    var formatOk = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value);
    var parts    = value.split('@');
    var domain   = parts.length === 2 ? parts[1].toLowerCase() : '';
    var disposable = DISPOSABLE_DOMAINS.indexOf(domain) !== -1;

    var valid = formatOk && !disposable;
    var msg   = '';

    if (!formatOk) {
        msg = '⚠ Format invalide. Exemple : prenom@gmail.com';
    } else if (disposable) {
        msg = '✕ Adresses email temporaires non acceptées.';
    } else {
        msg = '✓ Format valide.';
    }

    field.style.borderColor  = valid ? 'rgba(34,197,94,0.5)' : 'rgba(239,68,68,0.5)';
    field.style.boxShadow    = valid ? '0 0 0 3px rgba(34,197,94,0.07)' : '0 0 0 3px rgba(239,68,68,0.07)';

    indicator.innerHTML      = valid
        ? '<svg style="width:16px;height:16px;color:#22c55e;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>'
        : '<svg style="width:16px;height:16px;color:#ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
    indicator.style.display  = 'block';

    feedback.textContent     = msg;
    feedback.style.color     = valid ? '#4ade80' : '#fca5a5';
    feedback.style.display   = 'block';
}
</script>
</body>
</html>
