<x-public-layout title="Contact" description="Contactez la Ligue Régionale de Taekwondo de Fatick">

<style>
    .contact-card {
        background: rgba(255,255,255,0.025);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        padding: 2.5rem;
        transition: border-color 0.3s, background 0.3s;
    }
    .contact-card:hover {
        border-color: rgba(245,158,11,0.25);
        background: rgba(245,158,11,0.03);
    }
    .contact-icon-wrap {
        width: 52px; height: 52px;
        background: rgba(245,158,11,0.1);
        border: 1px solid rgba(245,158,11,0.2);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1.5rem;
        flex-shrink: 0;
    }
    .contact-link-btn {
        display: inline-flex; align-items: center; gap: 10px;
        background: rgba(245,158,11,0.08);
        border: 1px solid rgba(245,158,11,0.2);
        border-radius: 10px;
        padding: 12px 20px;
        color: #f59e0b;
        font-size: 0.9rem; font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        margin-top: 1.25rem;
        width: 100%;
        justify-content: center;
    }
    .contact-link-btn:hover {
        background: rgba(245,158,11,0.15);
        border-color: rgba(245,158,11,0.4);
        color: #fbbf24;
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(245,158,11,0.12);
    }
    .divider-line {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06) 30%, rgba(255,255,255,0.06) 70%, transparent);
        margin: 3rem 0;
    }
</style>

{{-- ── Hero ──────────────────────────────────────────────────────────────────── --}}
<section style="padding-top: 160px; padding-bottom: 80px; position: relative; overflow: hidden;">

    {{-- Ambient blobs --}}
    <div aria-hidden style="position: absolute; top: 80px; left: -120px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(245,158,11,0.07) 0%, transparent 70%); pointer-events: none;"></div>
    <div aria-hidden style="position: absolute; top: 60px; right: -80px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(245,158,11,0.05) 0%, transparent 70%); pointer-events: none;"></div>

    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative; z-index: 1; text-align: center;">

        <div style="display: inline-flex; align-items: center; gap: 10px; background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.18); border-radius: 100px; padding: 6px 18px; margin-bottom: 2rem;">
            <div style="width: 5px; height: 5px; background: #f59e0b; border-radius: 50%;"></div>
            <span style="font-size: 0.68rem; font-weight: 600; color: #f59e0b; letter-spacing: 0.2em; text-transform: uppercase;">Nous joindre</span>
        </div>

        <h1 style="font-size: clamp(2.5rem, 5vw, 3.8rem); font-weight: 800; color: #fff; line-height: 1.1; letter-spacing: -0.03em; margin-bottom: 1.25rem; font-family: 'Space Grotesk', sans-serif;">
            Contactez-nous
        </h1>
        <p style="font-size: 1.05rem; color: rgba(255,255,255,0.38); max-width: 520px; margin: 0 auto 3rem; line-height: 1.75;">
            Pour toute question sur les inscriptions, les événements ou la ligue, notre équipe est disponible pour vous répondre.
        </p>

    </div>
</section>

{{-- ── Cards contact ──────────────────────────────────────────────────────────── --}}
<section style="padding-bottom: 100px;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem;">

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; max-width: 900px; margin: 0 auto;">

            {{-- Email --}}
            <div class="contact-card">
                <div class="contact-icon-wrap">
                    <svg style="width: 22px; height: 22px; color: #f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                </div>
                <p style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(255,255,255,0.2); margin-bottom: 0.5rem;">Adresse email</p>
                <p style="font-size: 1rem; font-weight: 600; color: #fff; margin-bottom: 0.4rem; word-break: break-all;">contact@lrftaekwondo.com</p>
                <p style="font-size: 0.78rem; color: rgba(255,255,255,0.3); line-height: 1.6;">Inscriptions, informations générales, demandes administratives.</p>
                <a href="mailto:contact@lrftaekwondo.com" class="contact-link-btn">
                    <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    Envoyer un email
                </a>
            </div>

            {{-- Téléphone --}}
            <div class="contact-card">
                <div class="contact-icon-wrap">
                    <svg style="width: 22px; height: 22px; color: #f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                    </svg>
                </div>
                <p style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(255,255,255,0.2); margin-bottom: 0.5rem;">Téléphone</p>
                <p style="font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 0.4rem; letter-spacing: 0.04em;">77 305 69 98</p>
                <p style="font-size: 0.78rem; color: rgba(255,255,255,0.3); line-height: 1.6;">Disponible en semaine. Appel ou WhatsApp.</p>
                <a href="tel:+221773056998" class="contact-link-btn">
                    <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    Appeler maintenant
                </a>
            </div>

            {{-- Localisation --}}
            <div class="contact-card">
                <div class="contact-icon-wrap">
                    <svg style="width: 22px; height: 22px; color: #f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                    </svg>
                </div>
                <p style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(255,255,255,0.2); margin-bottom: 0.5rem;">Localisation</p>
                <p style="font-size: 1rem; font-weight: 600; color: #fff; margin-bottom: 0.4rem;">Fatick, Sénégal</p>
                <p style="font-size: 0.78rem; color: rgba(255,255,255,0.3); line-height: 1.6;">Ligue Régionale de Taekwondo de Fatick — L.R.F</p>
                <div style="margin-top: 1.25rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; padding: 18px; text-align: center;">
                    <p style="font-size: 0.72rem; color: rgba(255,255,255,0.18); letter-spacing: 0.08em; text-transform: uppercase;">Région de Fatick · Sénégal</p>
                </div>
            </div>

        </div>

        <div class="divider-line"></div>

        {{-- Liens rapides --}}
        <div style="max-width: 700px; margin: 0 auto; text-align: center;">
            <p style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(255,255,255,0.18); margin-bottom: 2rem;">Accès rapide</p>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 12px;">
                <a href="{{ route('public.inscription') }}"
                   style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: #f59e0b; color: #000; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; text-decoration: none; border-radius: 8px; transition: background 0.2s;"
                   onmouseover="this.style.background='#fbbf24'" onmouseout="this.style.background='#f59e0b'">
                    Inscrire un athlète
                    <svg style="width: 11px; height: 11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('public.verify') }}"
                   style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: transparent; color: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.1); font-size: 0.78rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; text-decoration: none; border-radius: 8px; transition: all 0.2s;"
                   onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.25)'" onmouseout="this.style.color='rgba(255,255,255,0.5)'; this.style.borderColor='rgba(255,255,255,0.1)'">
                    Vérifier mon inscription
                </a>
                <a href="{{ route('public.events') }}"
                   style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: transparent; color: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.1); font-size: 0.78rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; text-decoration: none; border-radius: 8px; transition: all 0.2s;"
                   onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.25)'" onmouseout="this.style.color='rgba(255,255,255,0.5)'; this.style.borderColor='rgba(255,255,255,0.1)'">
                    Voir les événements
                </a>
            </div>
        </div>

    </div>
</section>

</x-public-layout>
