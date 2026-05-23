<x-public-layout title="Politique de confidentialité" description="Politique de confidentialité et protection des données personnelles — Ligue de Fatick Taekwondo">

<style>
.legal-toc-link {
    display: block; color: rgba(255,255,255,0.28); font-size: 0.75rem;
    text-decoration: none; padding: 6px 0 6px 12px;
    border-left: 1px solid rgba(255,255,255,0.06);
    transition: color 0.2s, border-color 0.2s;
    line-height: 1.5;
}
.legal-toc-link:hover, .legal-toc-link.active { color: #f59e0b; border-color: #f59e0b; }
.legal-h2 { font-size: 1.1rem; font-weight: 800; color: #fff; margin: 2.5rem 0 1rem; padding-top: 0.5rem; display: flex; align-items: center; gap: 10px; scroll-margin-top: 100px; }
.legal-h2-num { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; background: rgba(245,158,11,0.12); color: #f59e0b; border-radius: 6px; font-size: 0.72rem; font-weight: 800; flex-shrink: 0; }
.legal-p { color: rgba(255,255,255,0.45); font-size: 0.875rem; line-height: 1.9; margin: 0 0 0.85rem; }
.legal-ul { margin: 0.5rem 0 1rem; padding-left: 0; list-style: none; }
.legal-ul li { color: rgba(255,255,255,0.4); font-size: 0.875rem; line-height: 1.8; padding: 3px 0 3px 18px; position: relative; }
.legal-ul li::before { content: '—'; position: absolute; left: 0; color: rgba(245,158,11,0.5); }
.legal-table { width: 100%; border-collapse: collapse; margin: 1rem 0 1.5rem; font-size: 0.8rem; }
.legal-table th { color: rgba(245,158,11,0.7); font-weight: 700; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; padding: 10px 14px; background: rgba(245,158,11,0.05); border: 1px solid rgba(255,255,255,0.07); text-align: left; }
.legal-table td { color: rgba(255,255,255,0.4); padding: 10px 14px; border: 1px solid rgba(255,255,255,0.06); vertical-align: top; line-height: 1.6; }
.legal-highlight { background: rgba(245,158,11,0.05); border: 1px solid rgba(245,158,11,0.15); border-radius: 8px; padding: 14px 18px; margin: 1rem 0; }
.legal-highlight p { color: rgba(245,158,11,0.8); font-size: 0.82rem; line-height: 1.7; margin: 0; }
@media (max-width: 900px) { .legal-layout { flex-direction: column !important; } .legal-toc-sidebar { display: none !important; } }
</style>

{{-- ── Header ──────────────────────────────────────────────────────────────────── --}}
<div style="background: #000; padding-top: 80px;">
    <div style="background: #000; padding: 5rem 0 3.5rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 700px; height: 280px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(245,158,11,0.07) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: rgba(255,255,255,0.05);"></div>
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 1.5rem;">
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Légal</span>
            </div>
            <h1 style="font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; color: #fff; line-height: 1.1; letter-spacing: -0.03em; margin: 0 0 1rem; font-family: 'Space Grotesk', sans-serif;">Politique de confidentialité</h1>
            <p style="color: rgba(255,255,255,0.3); font-size: 0.85rem; margin: 0;">
                Dernière mise à jour : <strong style="color: rgba(255,255,255,0.5);">23 mai 2026</strong>
                &nbsp;·&nbsp; Version 1.0
            </p>
        </div>
    </div>

    {{-- ── Layout ── --}}
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem 6rem; display: flex; gap: 4rem; align-items: flex-start;" class="legal-layout">

        {{-- Sidebar TOC --}}
        <nav class="legal-toc-sidebar" style="flex-shrink: 0; width: 220px; position: sticky; top: 100px;">
            <p style="color: rgba(255,255,255,0.18); font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.18em; margin-bottom: 12px;">Sommaire</p>
            <a href="#s1"  class="legal-toc-link">1. Identité du responsable</a>
            <a href="#s2"  class="legal-toc-link">2. Données collectées</a>
            <a href="#s3"  class="legal-toc-link">3. Finalités et bases légales</a>
            <a href="#s4"  class="legal-toc-link">4. Durée de conservation</a>
            <a href="#s5"  class="legal-toc-link">5. Destinataires</a>
            <a href="#s6"  class="legal-toc-link">6. Vos droits</a>
            <a href="#s7"  class="legal-toc-link">7. Protection des mineurs</a>
            <a href="#s8"  class="legal-toc-link">8. Sécurité</a>
            <a href="#s9"  class="legal-toc-link">9. Cookies</a>
            <a href="#s10" class="legal-toc-link">10. Modifications</a>
            <a href="#s11" class="legal-toc-link">11. Contact</a>
        </nav>

        {{-- Content --}}
        <div style="flex: 1; min-width: 0;">

            <div class="legal-highlight">
                <p>Cette politique décrit comment la <strong>Ligue Régionale de Fatick — Taekwondo (L.R.F)</strong> collecte, utilise et protège vos données personnelles sur la plateforme <strong>lrftaekwondo.com</strong>. Elle s'applique à tous les utilisateurs : coachs, staff, athlètes et visiteurs.</p>
            </div>

            {{-- 1 --}}
            <h2 class="legal-h2" id="s1"><span class="legal-h2-num">1</span> Identité du responsable du traitement</h2>
            <p class="legal-p">Le responsable du traitement des données personnelles est :</p>
            <table class="legal-table">
                <tr><th>Organisme</th><td>Ligue Régionale de Fatick — Taekwondo (L.R.F)</td></tr>
                <tr><th>Adresse</th><td>Fatick, République du Sénégal</td></tr>
                <tr><th>Email</th><td>contact@lrftaekwondo.com</td></tr>
                <tr><th>Téléphone</th><td>+221 77 305 69 98</td></tr>
                <tr><th>Site web</th><td>https://lrftaekwondo.com</td></tr>
                <tr><th>Affiliation</th><td>Fédération Sénégalaise de Taekwondo (FST)</td></tr>
            </table>

            {{-- 2 --}}
            <h2 class="legal-h2" id="s2"><span class="legal-h2-num">2</span> Données personnelles collectées</h2>
            <p class="legal-p">Nous collectons les catégories de données suivantes selon votre profil :</p>
            <table class="legal-table">
                <thead><tr><th>Catégorie</th><th>Données</th><th>Concerne</th></tr></thead>
                <tbody>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Identité</strong></td><td>Prénom, nom, date de naissance, lieu de naissance, nationalité, genre</td><td>Athlètes, coachs</td></tr>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Contact</strong></td><td>Adresse e-mail, numéro de téléphone</td><td>Coachs, staff</td></tr>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Sportives</strong></td><td>Club d'appartenance, catégorie d'âge, catégorie de poids, numéro de licence, code fédéral, résultats sportifs, tirages au sort</td><td>Athlètes</td></tr>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Financières</strong></td><td>Statut de paiement des droits d'inscription, montants réglés, date et mode de validation</td><td>Athlètes, coachs</td></tr>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Visuelles</strong></td><td>Photo de profil (athlète ou coach, optionnelle)</td><td>Athlètes, coachs</td></tr>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Connexion</strong></td><td>Adresse IP, horodatage des connexions, navigateur (journaux système)</td><td>Tous les utilisateurs</td></tr>
                </tbody>
            </table>
            <p class="legal-p">Nous ne collectons aucune donnée dite « sensible » au sens de la loi (données de santé, origines ethniques, opinions politiques ou religieuses) au-delà des données sportives nécessaires à la gestion des compétitions.</p>

            {{-- 3 --}}
            <h2 class="legal-h2" id="s3"><span class="legal-h2-num">3</span> Finalités et bases légales du traitement</h2>
            <table class="legal-table">
                <thead><tr><th>Finalité</th><th>Base légale</th></tr></thead>
                <tbody>
                    <tr><td>Gestion des inscriptions aux compétitions et événements sportifs</td><td>Exécution d'un contrat / Mission d'intérêt public (activité sportive fédérale)</td></tr>
                    <tr><td>Vérification de l'éligibilité sportive (catégorie d'âge, poids, licence)</td><td>Mission d'intérêt public — règlement fédéral</td></tr>
                    <tr><td>Publication des résultats, tirages et classements officiels</td><td>Mission d'intérêt public — transparence sportive</td></tr>
                    <tr><td>Gestion des comptes coachs et du personnel de la ligue</td><td>Exécution d'un contrat</td></tr>
                    <tr><td>Émission des reçus et suivi des paiements de droits d'inscription</td><td>Obligation légale comptable</td></tr>
                    <tr><td>Envoi de communications relatives aux événements (convocations, résultats)</td><td>Intérêt légitime de la ligue</td></tr>
                    <tr><td>Sécurité de la plateforme et prévention de la fraude</td><td>Intérêt légitime de la ligue</td></tr>
                    <tr><td>Génération de statistiques internes (anonymisées)</td><td>Intérêt légitime — amélioration du service</td></tr>
                </tbody>
            </table>

            {{-- 4 --}}
            <h2 class="legal-h2" id="s4"><span class="legal-h2-num">4</span> Durée de conservation</h2>
            <table class="legal-table">
                <thead><tr><th>Type de donnée</th><th>Durée de conservation</th></tr></thead>
                <tbody>
                    <tr><td>Données d'inscription athlète (actif)</td><td>Durée de la licence fédérale + 2 ans</td></tr>
                    <tr><td>Données d'inscription athlète (inactif / non renouvelé)</td><td>3 ans après la dernière activité</td></tr>
                    <tr><td>Données des coachs (compte actif)</td><td>Durée du compte + 1 an après suppression</td></tr>
                    <tr><td>Résultats et classements sportifs officiels</td><td>Conservation illimitée (archives historiques de la ligue)</td></tr>
                    <tr><td>Données financières (paiements, reçus)</td><td>10 ans (obligation comptable légale)</td></tr>
                    <tr><td>Journaux de connexion</td><td>6 mois</td></tr>
                    <tr><td>Photos de profil supprimées par l'utilisateur</td><td>Suppression immédiate du stockage</td></tr>
                </tbody>
            </table>

            {{-- 5 --}}
            <h2 class="legal-h2" id="s5"><span class="legal-h2-num">5</span> Destinataires des données</h2>
            <p class="legal-p">Vos données sont accessibles uniquement aux personnes habilitées selon les règles suivantes :</p>
            <ul class="legal-ul">
                <li><strong style="color:rgba(255,255,255,0.6)">Staff technique de la ligue</strong> — données sportives des athlètes inscrits aux événements qu'ils gèrent</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Staff financier</strong> — données d'identité et de paiement pour la gestion des droits d'inscription</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Administrateurs</strong> — accès complet dans le cadre de leurs fonctions de gestion de la ligue</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Coachs accrédités</strong> — accès limité aux données de leurs propres athlètes inscrits</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Fédération Sénégalaise de Taekwondo (FST)</strong> — transmission des résultats officiels conformément aux obligations fédérales</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Hébergeur (Hostinger)</strong> — accès technique minimal aux données stockées sur les serveurs dans le cadre du contrat d'hébergement</li>
            </ul>
            <p class="legal-p">Nous ne vendons, ne louons ni ne cédons vos données à des tiers à des fins commerciales.</p>

            {{-- 6 --}}
            <h2 class="legal-h2" id="s6"><span class="legal-h2-num">6</span> Vos droits sur vos données</h2>
            <p class="legal-p">Conformément à la <strong>Loi n° 2008-12 du 25 janvier 2008</strong> portant sur la Protection des données à caractère personnel au Sénégal, vous disposez des droits suivants :</p>
            <ul class="legal-ul">
                <li><strong style="color:rgba(255,255,255,0.6)">Droit d'accès</strong> — obtenir confirmation que des données vous concernant sont traitées et en recevoir une copie</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Droit de rectification</strong> — faire corriger toute donnée inexacte ou incomplète</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Droit d'effacement</strong> — demander la suppression de vos données, sous réserve des obligations légales de conservation</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Droit d'opposition</strong> — vous opposer à un traitement fondé sur l'intérêt légitime</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Droit à la limitation</strong> — demander la suspension temporaire du traitement de vos données</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Droit à la portabilité</strong> — recevoir vos données dans un format structuré et lisible par machine</li>
            </ul>
            <p class="legal-p">Pour exercer ces droits, contactez-nous à <strong style="color: #f59e0b;">contact@lrftaekwondo.com</strong> en précisant votre identité. Nous répondons dans un délai de <strong style="color:rgba(255,255,255,0.65)">30 jours</strong>.</p>
            <p class="legal-p">Vous pouvez également introduire une réclamation auprès de la <strong style="color:rgba(255,255,255,0.65)">Commission de Protection des Données Personnelles (CDP)</strong> du Sénégal — www.cdp.sn.</p>

            {{-- 7 --}}
            <h2 class="legal-h2" id="s7"><span class="legal-h2-num">7</span> Protection des mineurs</h2>
            <p class="legal-p">La plateforme accueille des données relatives à des athlètes mineurs (catégories Benjamin, Minime, Cadet — dès 8 ans). Nous appliquons les mesures suivantes :</p>
            <ul class="legal-ul">
                <li>Les données des mineurs ne sont accessibles qu'aux personnels habilités de la ligue et au coach référent</li>
                <li>Les photos d'athlètes mineurs publiées sur la plateforme (galerie, résultats) requièrent une autorisation parentale implicite via l'acte d'inscription fédérale</li>
                <li>Aucune donnée de mineur n'est utilisée à des fins commerciales ou publicitaires</li>
                <li>Les résultats publics affichent uniquement le nom, le club et la catégorie sportive — jamais la date de naissance complète</li>
            </ul>

            {{-- 8 --}}
            <h2 class="legal-h2" id="s8"><span class="legal-h2-num">8</span> Sécurité des données</h2>
            <p class="legal-p">Nous mettons en œuvre les mesures techniques et organisationnelles suivantes :</p>
            <ul class="legal-ul">
                <li>Chiffrement des mots de passe (bcrypt, facteur de coût 12)</li>
                <li>Communications chiffrées via HTTPS/TLS sur l'ensemble du site</li>
                <li>Contrôle d'accès basé sur les rôles (RBAC) — chaque utilisateur n'accède qu'aux données nécessaires à sa fonction</li>
                <li>Tokens CSRF sur tous les formulaires pour prévenir les attaques cross-site</li>
                <li>Sessions authentifiées avec expiration automatique (120 minutes d'inactivité)</li>
                <li>Sauvegardes régulières de la base de données par l'hébergeur</li>
                <li>Surveillance et journaux d'accès pour la détection d'anomalies</li>
            </ul>

            {{-- 9 --}}
            <h2 class="legal-h2" id="s9"><span class="legal-h2-num">9</span> Cookies et traceurs</h2>
            <p class="legal-p">La plateforme utilise un nombre minimal de cookies, tous nécessaires au fonctionnement :</p>
            <table class="legal-table">
                <thead><tr><th>Cookie</th><th>Type</th><th>Finalité</th><th>Durée</th></tr></thead>
                <tbody>
                    <tr><td><code style="color:#f59e0b;font-size:0.8rem;">lrftk_session</code></td><td>Fonctionnel</td><td>Maintien de la session utilisateur authentifié</td><td>Session (fermeture navigateur)</td></tr>
                    <tr><td><code style="color:#f59e0b;font-size:0.8rem;">XSRF-TOKEN</code></td><td>Sécurité</td><td>Protection contre les attaques CSRF</td><td>Session</td></tr>
                    <tr><td><code style="color:#f59e0b;font-size:0.8rem;">remember_web_*</code></td><td>Fonctionnel</td><td>Option « se souvenir de moi » à la connexion</td><td>400 jours (si activé)</td></tr>
                </tbody>
            </table>
            <p class="legal-p">Nous n'utilisons aucun cookie publicitaire, aucun outil de suivi comportemental tiers (Google Analytics, Facebook Pixel, etc.).</p>

            {{-- 10 --}}
            <h2 class="legal-h2" id="s10"><span class="legal-h2-num">10</span> Modifications de la politique</h2>
            <p class="legal-p">Nous nous réservons le droit de mettre à jour cette politique pour refléter les évolutions légales ou techniques. La date de dernière mise à jour est indiquée en haut de page. En cas de modification substantielle affectant vos droits, nous vous en informerons par e-mail ou par notification sur la plateforme.</p>

            {{-- 11 --}}
            <h2 class="legal-h2" id="s11"><span class="legal-h2-num">11</span> Contact et réclamations</h2>
            <p class="legal-p">Pour toute question relative à vos données personnelles :</p>
            <div class="legal-highlight">
                <p>
                    <strong style="color: #f59e0b;">Ligue Régionale de Fatick — Taekwondo (L.R.F)</strong><br>
                    E-mail : <a href="mailto:contact@lrftaekwondo.com" style="color:#f59e0b;text-decoration:none;">contact@lrftaekwondo.com</a><br>
                    Téléphone : +221 77 305 69 98<br>
                    Adresse : Fatick, Sénégal
                </p>
            </div>

            {{-- Nav liens --}}
            <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.06); display: flex; flex-wrap: wrap; gap: 12px;">
                <a href="{{ route('public.terms') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: rgba(255,255,255,0.4); font-size: 0.78rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.2)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.08)'">Conditions d'utilisation →</a>
                <a href="{{ route('public.data-compliance') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: rgba(255,255,255,0.4); font-size: 0.78rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.2)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.08)'">Conformité des données →</a>
                <a href="{{ route('public.intellectual-property') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: rgba(255,255,255,0.4); font-size: 0.78rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.2)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.08)'">Propriété intellectuelle →</a>
            </div>

        </div>
    </div>
</div>

</x-public-layout>
