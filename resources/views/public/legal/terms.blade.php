<x-public-layout title="Conditions d'utilisation" description="Conditions générales d'utilisation de la plateforme — Ligue de Fatick Taekwondo">

<style>
.legal-toc-link { display: block; color: rgba(255,255,255,0.28); font-size: 0.75rem; text-decoration: none; padding: 6px 0 6px 12px; border-left: 1px solid rgba(255,255,255,0.06); transition: color 0.2s, border-color 0.2s; line-height: 1.5; }
.legal-toc-link:hover, .legal-toc-link.active { color: #f59e0b; border-color: #f59e0b; }
.legal-h2 { font-size: 1.1rem; font-weight: 800; color: #fff; margin: 2.5rem 0 1rem; padding-top: 0.5rem; display: flex; align-items: center; gap: 10px; scroll-margin-top: 100px; }
.legal-h2-num { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; background: rgba(245,158,11,0.12); color: #f59e0b; border-radius: 6px; font-size: 0.72rem; font-weight: 800; flex-shrink: 0; }
.legal-h3 { font-size: 0.9rem; font-weight: 700; color: rgba(255,255,255,0.75); margin: 1.5rem 0 0.6rem; }
.legal-p { color: rgba(255,255,255,0.45); font-size: 0.875rem; line-height: 1.9; margin: 0 0 0.85rem; }
.legal-ul { margin: 0.5rem 0 1rem; padding-left: 0; list-style: none; }
.legal-ul li { color: rgba(255,255,255,0.4); font-size: 0.875rem; line-height: 1.8; padding: 3px 0 3px 18px; position: relative; }
.legal-ul li::before { content: '—'; position: absolute; left: 0; color: rgba(245,158,11,0.5); }
.legal-highlight { background: rgba(245,158,11,0.05); border: 1px solid rgba(245,158,11,0.15); border-radius: 8px; padding: 14px 18px; margin: 1rem 0; }
.legal-highlight p { color: rgba(245,158,11,0.8); font-size: 0.82rem; line-height: 1.7; margin: 0; }
.legal-warn { background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.18); border-radius: 8px; padding: 14px 18px; margin: 1rem 0; }
.legal-warn p { color: rgba(248,113,113,0.85); font-size: 0.82rem; line-height: 1.7; margin: 0; }
@media (max-width: 900px) { .legal-layout { flex-direction: column !important; } .legal-toc-sidebar { display: none !important; } }
</style>

{{-- ── Header ── --}}
<div style="background: #000; padding-top: 80px;">
    <div style="background: #000; padding: 5rem 0 3.5rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 700px; height: 280px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(245,158,11,0.07) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: rgba(255,255,255,0.05);"></div>
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 1.5rem;">
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Légal</span>
            </div>
            <h1 style="font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; color: #fff; line-height: 1.1; letter-spacing: -0.03em; margin: 0 0 1rem; font-family: 'Space Grotesk', sans-serif;">Conditions d'utilisation</h1>
            <p style="color: rgba(255,255,255,0.3); font-size: 0.85rem; margin: 0;">
                Dernière mise à jour : <strong style="color: rgba(255,255,255,0.5);">23 mai 2026</strong>
                &nbsp;·&nbsp; Version 1.0
            </p>
        </div>
    </div>

    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem 6rem; display: flex; gap: 4rem; align-items: flex-start;" class="legal-layout">

        {{-- Sidebar TOC --}}
        <nav class="legal-toc-sidebar" style="flex-shrink: 0; width: 220px; position: sticky; top: 100px;">
            <p style="color: rgba(255,255,255,0.18); font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.18em; margin-bottom: 12px;">Sommaire</p>
            <a href="#s1"  class="legal-toc-link">1. Objet</a>
            <a href="#s2"  class="legal-toc-link">2. Acceptation</a>
            <a href="#s3"  class="legal-toc-link">3. Description du service</a>
            <a href="#s4"  class="legal-toc-link">4. Accès et inscription</a>
            <a href="#s5"  class="legal-toc-link">5. Obligations des utilisateurs</a>
            <a href="#s6"  class="legal-toc-link">6. Utilisation interdite</a>
            <a href="#s7"  class="legal-toc-link">7. Responsabilité</a>
            <a href="#s8"  class="legal-toc-link">8. Suspension et résiliation</a>
            <a href="#s9"  class="legal-toc-link">9. Disponibilité</a>
            <a href="#s10" class="legal-toc-link">10. Droit applicable</a>
            <a href="#s11" class="legal-toc-link">11. Modifications</a>
            <a href="#s12" class="legal-toc-link">12. Contact</a>
        </nav>

        {{-- Content --}}
        <div style="flex: 1; min-width: 0;">

            <div class="legal-highlight">
                <p>En accédant à la plateforme <strong>lrftaekwondo.com</strong> et en utilisant ses services, vous acceptez sans réserve les présentes Conditions Générales d'Utilisation (CGU). Si vous n'acceptez pas ces conditions, vous devez cesser d'utiliser la plateforme.</p>
            </div>

            {{-- 1 --}}
            <h2 class="legal-h2" id="s1"><span class="legal-h2-num">1</span> Objet</h2>
            <p class="legal-p">Les présentes Conditions Générales d'Utilisation régissent l'accès et l'utilisation de la plateforme numérique de la <strong>Ligue Régionale de Fatick — Taekwondo (L.R.F)</strong>, accessible à l'adresse <strong>https://lrftaekwondo.com</strong>.</p>
            <p class="legal-p">Cette plateforme est un outil de gestion sportive à destination des coachs accrédités, du personnel de la ligue et, à titre consultatif, du public général pour la consultation des événements et résultats officiels.</p>

            {{-- 2 --}}
            <h2 class="legal-h2" id="s2"><span class="legal-h2-num">2</span> Acceptation des conditions</h2>
            <p class="legal-p">L'utilisation de la plateforme implique l'acceptation pleine et entière des présentes CGU. Cette acceptation se matérialise :</p>
            <ul class="legal-ul">
                <li>Par la création d'un compte coach sur la plateforme</li>
                <li>Par la soumission d'un formulaire d'inscription d'athlète</li>
                <li>Par toute navigation sur les pages authentifiées de la plateforme</li>
            </ul>
            <p class="legal-p">La L.R.F se réserve le droit de modifier ces CGU à tout moment. Les modifications prennent effet dès leur publication. L'utilisation continue de la plateforme après modification vaut acceptation des nouvelles conditions.</p>

            {{-- 3 --}}
            <h2 class="legal-h2" id="s3"><span class="legal-h2-num">3</span> Description du service</h2>
            <p class="legal-p">La plateforme <strong>lrftaekwondo.com</strong> offre les fonctionnalités suivantes :</p>

            <h3 class="legal-h3">Espace public (sans compte)</h3>
            <ul class="legal-ul">
                <li>Consultation du calendrier des événements et compétitions</li>
                <li>Consultation des résultats, tirages au sort et classements officiels</li>
                <li>Galerie photo des événements passés</li>
                <li>Actualités et informations de la ligue</li>
                <li>Vérification du statut d'inscription d'un athlète (par nom ou numéro de licence)</li>
                <li>Formulaire d'inscription d'un athlète (accessible aux coachs)</li>
            </ul>

            <h3 class="legal-h3">Espace coach (compte requis)</h3>
            <ul class="legal-ul">
                <li>Gestion et suivi des inscriptions de ses propres athlètes</li>
                <li>Consultation des statuts de validation et de paiement</li>
                <li>Inscription directe d'athlètes via l'interface dédiée</li>
            </ul>

            <h3 class="legal-h3">Espace administration (accès restreint)</h3>
            <ul class="legal-ul">
                <li>Gestion complète des athlètes, événements, coachs et résultats</li>
                <li>Gestion des paiements et émission de reçus officiels</li>
                <li>Génération des tirages au sort et publication des classements</li>
                <li>Administration des comptes et des rôles utilisateurs</li>
            </ul>

            {{-- 4 --}}
            <h2 class="legal-h2" id="s4"><span class="legal-h2-num">4</span> Accès et inscription</h2>

            <h3 class="legal-h3">4.1 Création de compte coach</h3>
            <p class="legal-p">L'accès aux fonctionnalités réservées aux coachs requiert la création d'un compte. Pour s'inscrire, le coach doit :</p>
            <ul class="legal-ul">
                <li>Être un coach accrédité ou affilié à un club reconnu par la L.R.F</li>
                <li>Fournir des informations exactes, complètes et à jour</li>
                <li>Choisir un mot de passe robuste et confidentiel</li>
                <li>Soumettre son compte à la validation du staff technique de la ligue</li>
            </ul>
            <p class="legal-p">Le compte devient opérationnel uniquement après validation par un administrateur de la ligue. La L.R.F se réserve le droit de refuser toute demande de création de compte sans avoir à en justifier la raison.</p>

            <h3 class="legal-h3">4.2 Confidentialité du compte</h3>
            <p class="legal-p">Chaque utilisateur est responsable de la confidentialité de ses identifiants. Tout accès effectué avec vos identifiants est présumé effectué par vous. En cas de compromission de votre compte, vous devez nous en informer immédiatement à <strong style="color:#f59e0b;">contact@lrftaekwondo.com</strong>.</p>

            <h3 class="legal-h3">4.3 Inscription d'athlètes</h3>
            <p class="legal-p">Les coachs sont responsables de l'exactitude des informations saisies pour chaque athlète inscrit. En soumettant une inscription, le coach certifie :</p>
            <ul class="legal-ul">
                <li>Avoir l'autorisation parentale ou tutoriale requise pour les athlètes mineurs</li>
                <li>Que les données fournies sont exactes et conformes aux pièces officielles</li>
                <li>Que l'athlète remplit les conditions d'éligibilité à la catégorie choisie</li>
            </ul>

            {{-- 5 --}}
            <h2 class="legal-h2" id="s5"><span class="legal-h2-num">5</span> Obligations des utilisateurs</h2>
            <p class="legal-p">Tout utilisateur de la plateforme s'engage à :</p>
            <ul class="legal-ul">
                <li>Utiliser la plateforme conformément à sa finalité sportive et administrative</li>
                <li>Ne transmettre que des informations véridiques, complètes et à jour</li>
                <li>Respecter les droits de toutes les personnes dont les données sont saisies</li>
                <li>Ne pas partager ses identifiants avec des tiers</li>
                <li>Signaler immédiatement tout dysfonctionnement ou usage suspect</li>
                <li>Respecter les délais d'inscription fixés pour chaque événement</li>
                <li>S'acquitter des droits d'inscription selon les modalités définies par la ligue</li>
            </ul>

            {{-- 6 --}}
            <h2 class="legal-h2" id="s6"><span class="legal-h2-num">6</span> Utilisations interdites</h2>
            <div class="legal-warn">
                <p>Les utilisations suivantes sont formellement interdites et peuvent entraîner une suspension immédiate du compte et des poursuites judiciaires.</p>
            </div>
            <ul class="legal-ul">
                <li>Toute tentative d'accès non autorisé aux données d'autres utilisateurs ou aux espaces d'administration</li>
                <li>La falsification ou la modification frauduleuse de données sportives (résultats, catégories, licences)</li>
                <li>L'inscription d'athlètes fictifs ou ne remplissant pas les conditions d'éligibilité</li>
                <li>L'utilisation d'outils automatisés (bots, scrapers) pour collecter des données de la plateforme</li>
                <li>Toute action visant à perturber, endommager ou surcharger les serveurs de la plateforme</li>
                <li>La revente ou la communication non autorisée des données extraites de la plateforme</li>
                <li>L'usurpation d'identité d'un autre coach, athlète ou membre du staff</li>
            </ul>

            {{-- 7 --}}
            <h2 class="legal-h2" id="s7"><span class="legal-h2-num">7</span> Limitation de responsabilité</h2>
            <p class="legal-p">La L.R.F met en œuvre tous les moyens raisonnables pour assurer la disponibilité et l'exactitude des informations publiées sur la plateforme. Cependant :</p>
            <ul class="legal-ul">
                <li>La L.R.F ne saurait être tenue responsable des erreurs de saisie commises par les coachs lors des inscriptions</li>
                <li>La L.R.F ne garantit pas une disponibilité ininterrompue du service (maintenance, incidents techniques)</li>
                <li>La L.R.F ne pourra être tenue responsable d'un préjudice indirect résultant de l'utilisation ou de l'impossibilité d'utiliser la plateforme</li>
                <li>Les résultats sportifs et tirages publiés font foi selon les procès-verbaux officiels des compétitions ; en cas de divergence, les documents papier officiels priment</li>
            </ul>

            {{-- 8 --}}
            <h2 class="legal-h2" id="s8"><span class="legal-h2-num">8</span> Suspension et résiliation de compte</h2>
            <p class="legal-p">La L.R.F se réserve le droit de suspendre ou de supprimer tout compte sans préavis dans les cas suivants :</p>
            <ul class="legal-ul">
                <li>Non-respect des présentes CGU</li>
                <li>Fourniture d'informations fausses ou mensongères</li>
                <li>Tentative d'accès non autorisé ou comportement frauduleux</li>
                <li>Perte de la qualité de coach accrédité par la ligue</li>
                <li>Demande de l'utilisateur lui-même</li>
            </ul>
            <p class="legal-p">En cas de suspension, les données de l'utilisateur sont conservées conformément aux durées prévues dans la <a href="{{ route('public.privacy') }}" style="color:#f59e0b;">Politique de confidentialité</a>.</p>

            {{-- 9 --}}
            <h2 class="legal-h2" id="s9"><span class="legal-h2-num">9</span> Disponibilité du service</h2>
            <p class="legal-p">La plateforme est accessible 24h/24 et 7j/7, sous réserve des opérations de maintenance, de mises à jour ou d'événements hors de notre contrôle (défaillance réseau, force majeure). La L.R.F s'efforcera d'informer les utilisateurs de toute interruption planifiée avec un préavis raisonnable via la plateforme ou par e-mail.</p>

            {{-- 10 --}}
            <h2 class="legal-h2" id="s10"><span class="legal-h2-num">10</span> Droit applicable et juridiction</h2>
            <p class="legal-p">Les présentes CGU sont régies par le <strong>droit sénégalais</strong>, notamment :</p>
            <ul class="legal-ul">
                <li>La Loi n° 2008-12 du 25 janvier 2008 sur la Protection des données à caractère personnel</li>
                <li>La Loi n° 2008-11 du 25 janvier 2008 sur la Cybercriminalité</li>
                <li>La Loi n° 2008-08 du 25 janvier 2008 sur les Transactions électroniques</li>
                <li>Les règlements sportifs de la Fédération Sénégalaise de Taekwondo (FST)</li>
            </ul>
            <p class="legal-p">Tout litige relatif à l'interprétation ou à l'exécution des présentes CGU sera soumis, à défaut de résolution amiable, à la compétence des tribunaux de <strong>Fatick, Sénégal</strong>.</p>

            {{-- 11 --}}
            <h2 class="legal-h2" id="s11"><span class="legal-h2-num">11</span> Modifications des CGU</h2>
            <p class="legal-p">La L.R.F peut modifier les présentes CGU à tout moment. Les utilisateurs seront informés des modifications par voie de notification sur la plateforme ou par e-mail. La poursuite de l'utilisation de la plateforme après notification vaut acceptation des nouvelles conditions.</p>

            {{-- 12 --}}
            <h2 class="legal-h2" id="s12"><span class="legal-h2-num">12</span> Contact</h2>
            <div class="legal-highlight">
                <p>
                    Pour toute question relative aux présentes CGU :<br><br>
                    <strong style="color:#f59e0b;">Ligue Régionale de Fatick — Taekwondo (L.R.F)</strong><br>
                    E-mail : <a href="mailto:contact@lrftaekwondo.com" style="color:#f59e0b;text-decoration:none;">contact@lrftaekwondo.com</a><br>
                    Téléphone : +221 77 305 69 98
                </p>
            </div>

            <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.06); display: flex; flex-wrap: wrap; gap: 12px;">
                <a href="{{ route('public.privacy') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: rgba(255,255,255,0.4); font-size: 0.78rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.2)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.08)'">← Politique de confidentialité</a>
                <a href="{{ route('public.data-compliance') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: rgba(255,255,255,0.4); font-size: 0.78rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.2)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.08)'">Conformité des données →</a>
            </div>
        </div>
    </div>
</div>

</x-public-layout>
