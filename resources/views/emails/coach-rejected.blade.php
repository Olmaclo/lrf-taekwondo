<x-mail-layout
    title="Demande de compte — LRF Taekwondo"
    preheader="Votre demande de compte coach n'a pas pu être approuvée pour le moment."
    accent="red">

    {{-- Badge --}}
    <x-mail-badge accent="red" label="Demande non approuvée" />

    {{-- Title --}}
    <h1 style="margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;font-size:26px;font-weight:700;color:#0f172a;letter-spacing:-0.5px;text-align:center;">
        Compte non activé
    </h1>
    <p style="margin:0 0 32px;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#64748b;text-align:center;line-height:1.5;">
        Contactez-nous si vous pensez qu'il s'agit d'une erreur.
    </p>

    {{-- Greeting --}}
    <p style="margin:0 0 24px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#334155;line-height:1.6;">
        Bonjour <strong style="color:#0f172a;">{{ $coach->name }}</strong>,
    </p>
    <p style="margin:0 0 28px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#334155;line-height:1.6;">
        Après examen de votre demande de compte coach sur la plateforme de la
        <strong style="color:#0f172a;">Ligue Régionale de Taekwondo de Fatick</strong>,
        nous ne sommes pas en mesure de l'<strong style="color:#dc2626;">approuver pour le moment</strong>.
    </p>

    {{-- Account info --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:28px;">
        <tr>
            <td style="padding:4px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Nom</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;color:#0f172a;">{{ $coach->name }}</span>
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

    {{-- Contact note --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="background-color:#f8fafc;border:1px solid #e2e8f0;border-left:4px solid #94a3b8;border-radius:6px;margin-bottom:28px;">
        <tr>
            <td style="padding:14px 18px;">
                <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#64748b;line-height:1.7;">
                    Si vous pensez qu'il s'agit d'une erreur ou si vous souhaitez obtenir plus d'informations sur
                    les conditions d'adhésion, veuillez contacter directement l'équipe technique de la Ligue.
                </p>
            </td>
        </tr>
    </table>

    {{-- Sign-off --}}
    <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#64748b;line-height:1.6;">
        Cordialement,<br>
        <strong style="color:#0f172a;">L'équipe de la Ligue Régionale de Taekwondo de Fatick</strong>
    </p>

</x-mail-layout>
