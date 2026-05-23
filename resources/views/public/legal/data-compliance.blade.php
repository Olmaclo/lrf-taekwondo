<x-public-layout title="Conformité des données" description="Conformité réglementaire et protection des données personnelles — Ligue de Fatick Taekwondo">

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
.legal-table { width: 100%; border-collapse: collapse; margin: 1rem 0 1.5rem; font-size: 0.8rem; }
.legal-table th { color: rgba(245,158,11,0.7); font-weight: 700; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; padding: 10px 14px; background: rgba(245,158,11,0.05); border: 1px solid rgba(255,255,255,0.07); text-align: left; }
.legal-table td { color: rgba(255,255,255,0.4); padding: 10px 14px; border: 1px solid rgba(255,255,255,0.06); vertical-align: top; line-height: 1.6; }
.legal-highlight { background: rgba(245,158,11,0.05); border: 1px solid rgba(245,158,11,0.15); border-radius: 8px; padding: 14px 18px; margin: 1rem 0; }
.legal-highlight p { color: rgba(245,158,11,0.8); font-size: 0.82rem; line-height: 1.7; margin: 0; }
.badge-compliant { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25); border-radius: 99px; color: #4ade80; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.06em; }
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
            <h1 style="font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; color: #fff; line-height: 1.1; letter-spacing: -0.03em; margin: 0 0 1rem; font-family: 'Space Grotesk', sans-serif;">Conformité des données</h1>
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px; margin-top: 0.75rem;">
                <p style="color: rgba(255,255,255,0.3); font-size: 0.85rem; margin: 0;">
                    Dernière mise à jour : <strong style="color: rgba(255,255,255,0.5);">23 mai 2026</strong>
                </p>
                <span class="badge-compliant">
                    <span style="width: 5px; height: 5px; border-radius: 50%; background: #4ade80; flex-shrink: 0;"></span>
                    Conforme — Loi n° 2008-12 Sénégal
                </span>
            </div>
        </div>
    </div>

    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem 6rem; display: flex; gap: 4rem; align-items: flex-start;" class="legal-layout">

        {{-- Sidebar TOC --}}
        <nav class="legal-toc-sidebar" style="flex-shrink: 0; width: 220px; position: sticky; top: 100px;">
            <p style="color: rgba(255,255,255,0.18); font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.18em; margin-bottom: 12px;">Sommaire</p>
            <a href="#s1"  class="legal-toc-link">1. Cadre réglementaire</a>
            <a href="#s2"  class="legal-toc-link">2. Responsable des données</a>
            <a href="#s3"  class="legal-toc-link">3. Registre des traitements</a>
            <a href="#s4"  class="legal-toc-link">4. Mesures de sécurité</a>
            <a href="#s5"  class="legal-toc-link">5. Sous-traitants</a>
            <a href="#s6"  class="legal-toc-link">6. Transferts internationaux</a>
            <a href="#s7"  class="legal-toc-link">7. Durées de conservation</a>
            <a href="#s8"  class="legal-toc-link">8. Droits des personnes</a>
            <a href="#s9"  class="legal-toc-link">9. Violations de données</a>
            <a href="#s10" class="legal-toc-link">10. Mise à jour</a>
        </nav>

        {{-- Content --}}
        <div style="flex: 1; min-width: 0;">

            <div class="legal-highlight">
                <p>Ce document décrit le cadre de conformité de la <strong>Ligue Régionale de Fatick — Taekwondo (L.R.F)</strong> en matière de protection des données personnelles, conformément à la législation sénégalaise en vigueur. Il s'adresse aux personnes souhaitant comprendre comment leurs droits sont garantis sur la plateforme.</p>
            </div>

            {{-- 1 --}}
            <h2 class="legal-h2" id="s1"><span class="legal-h2-num">1</span> Cadre réglementaire applicable</h2>
            <p class="legal-p">La L.R.F se conforme aux textes réglementaires suivants :</p>
            <table class="legal-table">
                <thead><tr><th>Texte</th><th>Objet</th></tr></thead>
                <tbody>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Loi n° 2008-12 du 25 janvier 2008</strong></td><td>Protection des données à caractère personnel au Sénégal</td></tr>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Loi n° 2008-11 du 25 janvier 2008</strong></td><td>Cybercriminalité au Sénégal</td></tr>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Loi n° 2008-08 du 25 janvier 2008</strong></td><td>Transactions électroniques</td></tr>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Décret n° 2008-721 du 30 juin 2008</strong></td><td>Application de la loi sur la protection des données</td></tr>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Règlements FST</strong></td><td>Fédération Sénégalaise de Taekwondo — règlement sportif et administratif</td></tr>
                </tbody>
            </table>
            <p class="legal-p">L'autorité de contrôle compétente est la <strong>Commission de Protection des Données Personnelles (CDP)</strong> du Sénégal, dont le site officiel est accessible à l'adresse <strong style="color:rgba(255,255,255,0.55);">www.cdp.sn</strong>.</p>

            {{-- 2 --}}
            <h2 class="legal-h2" id="s2"><span class="legal-h2-num">2</span> Responsable des données</h2>
            <p class="legal-p">Le responsable du traitement au sens de la Loi n° 2008-12 est :</p>
            <table class="legal-table">
                <tr><th>Qualité</th><td>Responsable du traitement</td></tr>
                <tr><th>Organisme</th><td>Ligue Régionale de Fatick — Taekwondo (L.R.F)</td></tr>
                <tr><th>Représentant légal</th><td>Président de la Ligue Régionale de Fatick</td></tr>
                <tr><th>Contact données</th><td><a href="mailto:contact@lrftaekwondo.com" style="color:#f59e0b;text-decoration:none;">contact@lrftaekwondo.com</a></td></tr>
            </table>

            {{-- 3 --}}
            <h2 class="legal-h2" id="s3"><span class="legal-h2-num">3</span> Registre des activités de traitement</h2>
            <p class="legal-p">Conformément à l'article 18 de la Loi n° 2008-12, la L.R.F tient un registre interne des activités de traitement. En voici le résumé public :</p>
            <table class="legal-table">
                <thead><tr><th>Traitement</th><th>Finalité</th><th>Base légale</th><th>Durée</th></tr></thead>
                <tbody>
                    <tr><td>Gestion des inscriptions compétition</td><td>Permettre la participation des athlètes aux événements</td><td>Mission d'intérêt public</td><td>Durée de licence + 2 ans</td></tr>
                    <tr><td>Comptes coachs</td><td>Accès à la plateforme de gestion</td><td>Contrat</td><td>Durée du compte + 1 an</td></tr>
                    <tr><td>Suivi des paiements</td><td>Comptabilité et émission de reçus</td><td>Obligation légale</td><td>10 ans</td></tr>
                    <tr><td>Publication résultats sportifs</td><td>Transparence sportive officielle</td><td>Mission d'intérêt public</td><td>Archives permanentes</td></tr>
                    <tr><td>Galerie photo</td><td>Communication et mémoire sportive</td><td>Intérêt légitime + consentement</td><td>Durée de publication</td></tr>
                    <tr><td>Journaux de connexion</td><td>Sécurité de la plateforme</td><td>Intérêt légitime</td><td>6 mois</td></tr>
                </tbody>
            </table>

            {{-- 4 --}}
            <h2 class="legal-h2" id="s4"><span class="legal-h2-num">4</span> Mesures de sécurité techniques et organisationnelles</h2>

            <h3 class="legal-h3">4.1 Mesures techniques</h3>
            <ul class="legal-ul">
                <li><strong style="color:rgba(255,255,255,0.6)">Chiffrement en transit</strong> — HTTPS/TLS sur l'intégralité du site (certificat SSL actif)</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Chiffrement des mots de passe</strong> — algorithme bcrypt avec facteur de coût 12 (irréversible)</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Protection CSRF</strong> — token anti-falsification sur tous les formulaires</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Contrôle d'accès basé sur les rôles (RBAC)</strong> — 5 niveaux de droits distincts (admin, technique, financier, coach, public)</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Expiration des sessions</strong> — déconnexion automatique après 120 minutes d'inactivité</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Sauvegardes</strong> — sauvegardes automatiques quotidiennes par l'hébergeur (Hostinger)</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Journaux d'accès</strong> — conservation 6 mois pour la détection d'anomalies</li>
            </ul>

            <h3 class="legal-h3">4.2 Mesures organisationnelles</h3>
            <ul class="legal-ul">
                <li>Principe du moindre privilège : chaque rôle n'accède qu'aux données strictement nécessaires</li>
                <li>Validation manuelle des comptes coachs par le staff avant activation</li>
                <li>Procédure de signalement des incidents de sécurité documentée</li>
                <li>Revue périodique des accès et des comptes actifs</li>
                <li>Les membres du staff avec accès aux données sont informés de leurs obligations de confidentialité</li>
            </ul>

            {{-- 5 --}}
            <h2 class="legal-h2" id="s5"><span class="legal-h2-num">5</span> Sous-traitants et partenaires techniques</h2>
            <p class="legal-p">La L.R.F fait appel aux sous-traitants suivants pour l'exploitation de la plateforme :</p>
            <table class="legal-table">
                <thead><tr><th>Sous-traitant</th><th>Rôle</th><th>Données transmises</th><th>Localisation</th></tr></thead>
                <tbody>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Hostinger International Ltd</strong></td><td>Hébergement web et base de données</td><td>Toutes les données stockées sur la plateforme</td><td>Lituanie / UE</td></tr>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Hostinger SMTP</strong></td><td>Envoi d'e-mails transactionnels (notifications, réinitialisation)</td><td>Adresse e-mail du destinataire, contenu du message</td><td>UE</td></tr>
                    <tr><td><strong style="color:rgba(255,255,255,0.65)">Google Fonts</strong></td><td>Fourniture des polices d'écriture (Space Grotesk, Inter)</td><td>Adresse IP de l'utilisateur (requête CDN)</td><td>USA</td></tr>
                </tbody>
            </table>
            <p class="legal-p">Chacun de ces sous-traitants est lié par des obligations contractuelles de protection des données. Hostinger est conforme au RGPD européen, offrant un niveau de protection équivalent ou supérieur aux exigences de la Loi n° 2008-12.</p>

            {{-- 6 --}}
            <h2 class="legal-h2" id="s6"><span class="legal-h2-num">6</span> Transferts internationaux de données</h2>
            <p class="legal-p">Les données sont principalement hébergées en <strong>Union Européenne</strong> (serveurs Hostinger en Lituanie), territoire offrant un niveau de protection des données équivalent aux standards internationaux.</p>
            <p class="legal-p">Le seul transfert vers les États-Unis concerne les requêtes vers le CDN Google Fonts (adresse IP uniquement, sans donnée nominative). Ce transfert est limité aux seules données techniques de connexion et ne comprend aucune donnée à caractère personnel au sens de la loi.</p>

            {{-- 7 --}}
            <h2 class="legal-h2" id="s7"><span class="legal-h2-num">7</span> Politique de conservation des données</h2>
            <p class="legal-p">Les données sont conservées uniquement pendant la durée nécessaire à leur finalité. Passé ce délai, elles sont supprimées ou anonymisées.</p>
            <table class="legal-table">
                <thead><tr><th>Catégorie</th><th>Durée</th><th>Traitement en fin de durée</th></tr></thead>
                <tbody>
                    <tr><td>Athlètes actifs (licence valide)</td><td>Durée de la licence + 2 ans</td><td>Anonymisation des données personnelles, conservation des stats sportives</td></tr>
                    <tr><td>Athlètes inactifs</td><td>3 ans après dernière activité</td><td>Suppression complète</td></tr>
                    <tr><td>Comptes coachs</td><td>Durée du compte + 1 an</td><td>Suppression</td></tr>
                    <tr><td>Paiements et reçus</td><td>10 ans</td><td>Archivage comptable hors ligne</td></tr>
                    <tr><td>Résultats officiels</td><td>Illimitée</td><td>Archives historiques anonymisées</td></tr>
                    <tr><td>Journaux système</td><td>6 mois</td><td>Suppression automatique</td></tr>
                </tbody>
            </table>

            {{-- 8 --}}
            <h2 class="legal-h2" id="s8"><span class="legal-h2-num">8</span> Exercice des droits des personnes</h2>
            <p class="legal-p">Toute personne concernée par un traitement peut exercer ses droits (accès, rectification, effacement, opposition, limitation, portabilité) en adressant une demande écrite à :</p>
            <div class="legal-highlight">
                <p>
                    <strong style="color:#f59e0b;">Ligue Régionale de Fatick — Taekwondo (L.R.F)</strong><br>
                    E-mail : <a href="mailto:contact@lrftaekwondo.com" style="color:#f59e0b;text-decoration:none;">contact@lrftaekwondo.com</a><br>
                    Objet du message : <em>"Exercice de droits — Protection des données"</em><br>
                    Pièce jointe : copie d'une pièce d'identité valide
                </p>
            </div>
            <p class="legal-p">Nous nous engageons à répondre dans un délai de <strong style="color:rgba(255,255,255,0.65)">30 jours calendaires</strong> à compter de la réception de la demande. En cas de demande complexe, ce délai peut être prorogé de 30 jours supplémentaires avec notification préalable.</p>
            <p class="legal-p">En cas de réponse insatisfaisante, vous pouvez saisir la <strong>Commission de Protection des Données Personnelles (CDP)</strong> du Sénégal : <strong style="color:rgba(255,255,255,0.55)">www.cdp.sn</strong>.</p>

            {{-- 9 --}}
            <h2 class="legal-h2" id="s9"><span class="legal-h2-num">9</span> Procédure en cas de violation de données</h2>
            <p class="legal-p">En cas de violation de données personnelles (accès non autorisé, perte, destruction accidentelle), la L.R.F s'engage à :</p>
            <ul class="legal-ul">
                <li><strong style="color:rgba(255,255,255,0.6)">Délai d'identification</strong> — détecter et qualifier l'incident dès sa découverte</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Notification à la CDP</strong> — notifier la Commission de Protection des Données Personnelles dans les 72 heures si la violation est susceptible d'engendrer un risque pour les droits et libertés des personnes</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Notification aux personnes</strong> — informer les personnes concernées dans les meilleurs délais si la violation est susceptible d'engendrer un risque élevé pour leurs droits</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Mesures correctives</strong> — mettre en place immédiatement les mesures nécessaires pour contenir la violation et prévenir toute récidive</li>
                <li><strong style="color:rgba(255,255,255,0.6)">Documentation</strong> — consigner l'incident dans un registre interne avec les circonstances, effets et mesures correctives</li>
            </ul>

            {{-- 10 --}}
            <h2 class="legal-h2" id="s10"><span class="legal-h2-num">10</span> Mise à jour de la conformité</h2>
            <p class="legal-p">Ce document est révisé au minimum une fois par an ou à chaque évolution significative des traitements ou de la réglementation applicable. La date de dernière révision est indiquée en haut de page. Toute question relative à la conformité peut être adressée à <a href="mailto:contact@lrftaekwondo.com" style="color:#f59e0b;text-decoration:none;">contact@lrftaekwondo.com</a>.</p>

            <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.06); display: flex; flex-wrap: wrap; gap: 12px;">
                <a href="{{ route('public.privacy') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: rgba(255,255,255,0.4); font-size: 0.78rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.2)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.08)'">← Politique de confidentialité</a>
                <a href="{{ route('public.intellectual-property') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: rgba(255,255,255,0.4); font-size: 0.78rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.2)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.08)'">Propriété intellectuelle →</a>
            </div>
        </div>
    </div>
</div>

</x-public-layout>
