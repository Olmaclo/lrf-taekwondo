<x-mail-layout
    title="Nouveau coach inscrit — LRF Taekwondo"
    preheader="Un nouveau coach {{ $coach->name }} attend votre validation sur la plateforme."
    accent="blue">

    {{-- Badge --}}
    <x-mail-badge accent="blue" label="● Action requise" />

    {{-- Title --}}
    <h1 style="margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;font-size:26px;font-weight:700;color:#0f172a;letter-spacing:-0.5px;text-align:center;">
        Nouveau coach inscrit
    </h1>
    <p style="margin:0 0 32px;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#64748b;text-align:center;line-height:1.5;">
        Un coach en attente de validation vient de rejoindre la plateforme.
    </p>

    {{-- Intro --}}
    <p style="margin:0 0 24px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#334155;line-height:1.6;">
        Un nouveau coach s'est inscrit sur la plateforme et attend votre validation avant de pouvoir accéder à ses fonctionnalités.
    </p>

    {{-- Coach info card --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:28px;">
        <tr>
            <td style="padding:4px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Nom complet</span><br>
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
                        <td style="padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Club</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#334155;">{{ $coach->club ?? '—' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Numéro de licence</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#334155;font-weight:600;">{{ $coach->license_number ?? '—' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Code fédéral</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#334155;font-weight:600;">{{ $coach->federal_code ?? '—' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 20px;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Inscrit le</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#334155;">{{ $coach->created_at->format('d/m/Y à H:i') }}</span>
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
                <a href="{{ config('app.url') }}/dashboard"
                   style="display:inline-block;background-color:#2563eb;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;letter-spacing:0.04em;text-decoration:none;padding:14px 36px;border-radius:6px;">
                    Valider depuis le tableau de bord
                </a>
            </td>
        </tr>
    </table>

    {{-- Sign-off --}}
    <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#94a3b8;line-height:1.6;text-align:center;">
        Notification automatique — Plateforme LRF Taekwondo
    </p>

</x-mail-layout>
