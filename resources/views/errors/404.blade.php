<!DOCTYPE html>
<html lang="fr" style="scroll-behavior:smooth;background:#06060a;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>404 — Page introuvable · Ligue de Fatick</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            background: #06060a;
            color: #fff;
            font-family: 'Space Grotesk', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .scene {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            overflow: hidden;
        }

        /* ── Background grid ── */
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(245,158,11,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(245,158,11,0.025) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        /* ── Radial glow center ── */
        .bg-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 900px;
            height: 600px;
            background: radial-gradient(ellipse, rgba(245,158,11,0.06) 0%, transparent 65%);
            pointer-events: none;
            animation: glowPulse 5s ease-in-out infinite;
        }

        /* ── Orbiting particles ── */
        .particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: #f59e0b;
            border-radius: 50%;
            opacity: 0;
            animation: floatParticle var(--dur, 8s) var(--delay, 0s) ease-in-out infinite;
        }

        /* ── 404 number ── */
        .num-404 {
            position: relative;
            font-size: clamp(7rem, 22vw, 18rem);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -0.06em;
            color: transparent;
            -webkit-text-stroke: 1px rgba(245,158,11,0.2);
            text-transform: uppercase;
            user-select: none;
            animation: glitchBase 6s steps(1) infinite;
            z-index: 1;
        }
        .num-404::before,
        .num-404::after {
            content: '404';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(245,158,11,0.55) 0%, rgba(245,158,11,0.0) 60%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            -webkit-text-stroke: 0;
        }
        .num-404::before {
            animation: glitchBefore 6s steps(1) infinite;
            clip-path: polygon(0 0, 100% 0, 100% 35%, 0 35%);
        }
        .num-404::after {
            animation: glitchAfter 6s steps(1) infinite;
            clip-path: polygon(0 60%, 100% 60%, 100% 100%, 0 100%);
            background: linear-gradient(135deg, rgba(245,158,11,0.35) 0%, rgba(245,158,11,0.0) 60%);
            -webkit-background-clip: text;
            background-clip: text;
        }

        /* ── Scan line ── */
        .scan-line {
            position: absolute;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to right, transparent, rgba(245,158,11,0.5), transparent);
            animation: scanDown 4s linear infinite;
            pointer-events: none;
        }

        /* ── Content ── */
        .content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 600px;
        }

        .label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 1.2rem;
        }
        .label-bar { width: 28px; height: 2px; background: #f59e0b; }
        .label-text {
            font-size: 0.58rem;
            font-weight: 700;
            color: #f59e0b;
            letter-spacing: 0.32em;
            text-transform: uppercase;
        }

        .title {
            font-size: clamp(1.4rem, 4vw, 2.4rem);
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
            line-height: 1.1;
        }

        .subtitle {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.38);
            line-height: 1.7;
            margin-bottom: 2.5rem;
            font-weight: 400;
        }

        /* ── CTA ── */
        .cta-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            background: #f59e0b;
            color: #000;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            text-decoration: none;
            padding: 14px 28px;
            font-family: 'Space Grotesk', sans-serif;
            transition: background 0.2s;
        }
        .btn-primary:hover { background: #fbbf24; }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            background: transparent;
            color: rgba(255,255,255,0.4);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            text-decoration: none;
            padding: 14px 28px;
            border: 1px solid rgba(255,255,255,0.1);
            font-family: 'Space Grotesk', sans-serif;
            transition: all 0.2s;
        }
        .btn-secondary:hover { color: #f59e0b; border-color: rgba(245,158,11,0.4); }

        /* ── Corner decorations ── */
        .corner {
            position: absolute;
            width: 20px;
            height: 20px;
            pointer-events: none;
        }
        .corner-tl { top: 2rem; left: 2rem; border-top: 2px solid rgba(245,158,11,0.4); border-left: 2px solid rgba(245,158,11,0.4); animation: cornerPulse 4s ease-in-out infinite; }
        .corner-tr { top: 2rem; right: 2rem; border-top: 2px solid rgba(245,158,11,0.4); border-right: 2px solid rgba(245,158,11,0.4); animation: cornerPulse 4s ease-in-out infinite 1s; }
        .corner-bl { bottom: 2rem; left: 2rem; border-bottom: 2px solid rgba(245,158,11,0.4); border-left: 2px solid rgba(245,158,11,0.4); animation: cornerPulse 4s ease-in-out infinite 2s; }
        .corner-br { bottom: 2rem; right: 2rem; border-bottom: 2px solid rgba(245,158,11,0.4); border-right: 2px solid rgba(245,158,11,0.4); animation: cornerPulse 4s ease-in-out infinite 3s; }

        /* ── Animations ── */
        @keyframes glowPulse {
            0%,100% { opacity: 1; transform: translate(-50%,-50%) scale(1); }
            50%      { opacity: 0.7; transform: translate(-50%,-50%) scale(1.05); }
        }

        @keyframes scanDown {
            0%   { top: -2px; opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        @keyframes glitchBase {
            0%,94%,100% { transform: translate(0,0); }
            95%          { transform: translate(-3px,0); }
            97%          { transform: translate(3px,0); }
        }
        @keyframes glitchBefore {
            0%,94%,100%  { transform: translate(0,0); opacity: 1; }
            95%           { transform: translate(4px,-2px); opacity: 0.8; }
            97%           { transform: translate(-4px,2px); opacity: 0.8; }
        }
        @keyframes glitchAfter {
            0%,96%,100%  { transform: translate(0,0); opacity: 1; }
            97%           { transform: translate(-3px,3px); opacity: 0.7; }
            99%           { transform: translate(3px,-3px); opacity: 0.7; }
        }

        @keyframes floatParticle {
            0%   { transform: translateY(100vh) translateX(0); opacity: 0; }
            10%  { opacity: 0.6; }
            90%  { opacity: 0.3; }
            100% { transform: translateY(-20vh) translateX(var(--drift, 0px)); opacity: 0; }
        }

        @keyframes cornerPulse {
            0%,100% { opacity: 0.4; }
            50%      { opacity: 0.9; }
        }
    </style>
</head>
<body>
<div class="scene">
    <div class="bg-grid"></div>
    <div class="bg-glow"></div>
    <div class="scan-line"></div>

    {{-- Floating particles --}}
    <div class="particles" id="particles"></div>

    {{-- Corner decorations --}}
    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-bl"></div>
    <div class="corner corner-br"></div>

    {{-- Big 404 --}}
    <div class="num-404" aria-hidden="true">404</div>

    {{-- Content --}}
    <div class="content">
        <div class="label">
            <div class="label-bar"></div>
            <span class="label-text">Erreur 404</span>
            <div class="label-bar"></div>
        </div>
        <h1 class="title">Page introuvable</h1>
        <p class="subtitle">
            La page que vous cherchez n'existe pas ou a été déplacée.<br>
            Vérifiez l'adresse ou retournez à l'accueil.
        </p>
        <div class="cta-row">
            <a href="/" class="btn-primary">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Retour à l'accueil
            </a>
            <a href="javascript:history.back()" class="btn-secondary">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Page précédente
            </a>
        </div>
    </div>
</div>

<script>
    // Spawn floating particles
    const container = document.getElementById('particles');
    for (let i = 0; i < 18; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.setProperty('--dur', (6 + Math.random() * 8) + 's');
        p.style.setProperty('--delay', (Math.random() * 8) + 's');
        p.style.setProperty('--drift', ((Math.random() - 0.5) * 120) + 'px');
        p.style.left = Math.random() * 100 + '%';
        p.style.bottom = '-10px';
        container.appendChild(p);
    }
</script>
</body>
</html>
