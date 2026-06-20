<x-mail-layout
    title="Compte approuvé — LRF Taekwondo"
    preheader="Votre compte coach sur la plateforme LRF Taekwondo a été approuvé."
    accent="green">

    {{-- Badge --}}
    <x-mail-badge accent="green" label="✓ Compte approuvé" />

    {{-- Title --}}
    <h1 style="margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;font-size:26px;font-weight:700;color:#0f172a;letter-spacing:-0.5px;text-align:center;">
        Votre accès est activé
    </h1>
    <p style="margin:0 0 32px;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#64748b;text-align:center;line-height:1.5;">
        Vous pouvez maintenant inscrire vos athlètes aux compétitions.
    </p>

    {{-- Greeting --}}
    <p style="margin:0 0 24px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#334155;line-height:1.6;">
        Bonjour <strong style="color:#0f172a;">{{ $coach->name }}</strong>,
    </p>
    <p style="margin:0 0 28px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#334155;line-height:1.6;">
        Bonne nouvelle ! Votre compte coach sur la plateforme de la
        <strong style="color:#0f172a;">Ligue Régionale de Taekwondo de Fatick</strong> a été
        <strong style="color:#16a34a;">approuvé</strong> par l'équipe d'administration.
        Vous pouvez désormais vous connecter et gérer vos inscriptions.
    </p>

    {{-- Account info card --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:28px;">
        <tr>
            <td style="padding:4px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Nom du compte</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;color:#0f172a;">{{ $coach->name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Adresse email</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#334155;">{{ $coach->email }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 20px;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Club</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#334155;">{{ $coach->club ?? '—' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- What can you do --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="background-color:#f0fdf4;border:1px solid #bbf7d0;border-radius:6px;margin-bottom:28px;">
        <tr>
            <td style="padding:16px 20px;">
                <p style="margin:0 0 10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#14532d;">
                    Vous pouvez maintenant
                </p>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td style="padding:3px 0;">
                            <span style="display:inline-block;width:18px;font-size:12px;color:#16a34a;">✓</span>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#166534;">Inscrire vos athlètes aux compétitions ouvertes</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:3px 0;">
                            <span style="display:inline-block;width:18px;font-size:12px;color:#16a34a;">✓</span>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#166534;">Suivre le statut de vos inscriptions en temps réel</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:3px 0;">
                            <span style="display:inline-block;width:18px;font-size:12px;color:#16a34a;">✓</span>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#166534;">Consulter les tirages et résultats officiels</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- CTA Button --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom:28px;">
        <tr>
            <td align="center">
                <a href="{{ config('app.url') }}/inscription"
                   style="display:inline-block;background-color:#16a34a;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;letter-spacing:0.04em;text-decoration:none;padding:14px 36px;border-radius:6px;">
                    Accéder à la plateforme
                </a>
            </td>
        </tr>
    </table>

    {{-- Help note --}}
    <p style="margin:0 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#94a3b8;line-height:1.6;text-align:center;">
        Problème de connexion ? Utilisez la fonction <em>« Mot de passe oublié »</em> sur la page de connexion.
    </p>

    {{-- Sign-off --}}
    <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#64748b;line-height:1.6;">
        Cordialement,<br>
        <strong style="color:#0f172a;">L'équipe de la Ligue Régionale de Taekwondo de Fatick</strong>
    </p>

</x-mail-layout>
