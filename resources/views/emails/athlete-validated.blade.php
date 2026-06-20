<x-mail-layout
    title="Inscription validée — LRF Taekwondo"
    preheader="Bonne nouvelle ! L'inscription de {{ $athlete->full_name }} a été validée."
    accent="green">

    {{-- Badge --}}
    <x-mail-badge accent="green" label="✓ Inscription validée" />

    {{-- Title --}}
    <h1 style="margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;font-size:26px;font-weight:700;color:#0f172a;letter-spacing:-0.5px;text-align:center;">
        Inscription confirmée
    </h1>
    <p style="margin:0 0 32px;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#64748b;text-align:center;line-height:1.5;">
        La participation de votre athlète est officiellement enregistrée.
    </p>

    {{-- Greeting --}}
    <p style="margin:0 0 24px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#334155;line-height:1.6;">
        Bonjour <strong style="color:#0f172a;">{{ $athlete->coach?->name ?? 'Coach' }}</strong>,
    </p>
    <p style="margin:0 0 28px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#334155;line-height:1.6;">
        Nous avons le plaisir de vous informer que l'inscription de votre athlète a été
        <strong style="color:#16a34a;">validée</strong> par l'équipe technique de la Ligue Régionale de Taekwondo de Fatick.
    </p>

    {{-- Info card --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:28px;">
        <tr>
            <td style="padding:4px 0;">

                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Athlète</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;color:#0f172a;">{{ $athlete->full_name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Événement</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#334155;">{{ $athlete->event?->name ?? '—' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Catégorie</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#334155;">
                                {{ $athlete->age_category }}
                                &nbsp;·&nbsp; {{ $athlete->gender === 'M' ? 'Masculin' : 'Féminin' }}
                                &nbsp;·&nbsp; {{ $athlete->weight_category }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 20px;{{ $athlete->license_number ? 'border-bottom:1px solid #e2e8f0;' : '' }}">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Club</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#334155;">{{ $athlete->club }}</span>
                        </td>
                    </tr>
                    @if($athlete->license_number)
                    <tr>
                        <td style="padding:12px 20px;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Numéro de licence</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#334155;font-weight:600;">{{ $athlete->license_number }}</span>
                        </td>
                    </tr>
                    @endif
                </table>

            </td>
        </tr>
    </table>

    {{-- CTA Button --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom:28px;">
        <tr>
            <td align="center">
                <a href="{{ config('app.url') }}/verifier-inscription"
                   style="display:inline-block;background-color:#16a34a;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;letter-spacing:0.04em;text-decoration:none;padding:14px 36px;border-radius:6px;">
                    Vérifier l'inscription en ligne
                </a>
            </td>
        </tr>
    </table>

    {{-- Sign-off --}}
    <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#64748b;line-height:1.6;">
        Cordialement,<br>
        <strong style="color:#0f172a;">L'équipe de la Ligue Régionale de Taekwondo de Fatick</strong>
    </p>

</x-mail-layout>
