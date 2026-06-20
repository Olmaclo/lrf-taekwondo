<x-mail-layout
    title="Inscription refusée — LRF Taekwondo"
    preheader="L'inscription de {{ $athlete->full_name }} n'a pas pu être validée."
    accent="red">

    {{-- Badge --}}
    <x-mail-badge accent="red" label="✗ Inscription refusée" />

    {{-- Title --}}
    <h1 style="margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;font-size:26px;font-weight:700;color:#0f172a;letter-spacing:-0.5px;text-align:center;">
        Inscription non validée
    </h1>
    <p style="margin:0 0 32px;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#64748b;text-align:center;line-height:1.5;">
        Nous vous invitons à contacter l'équipe technique pour plus d'informations.
    </p>

    {{-- Greeting --}}
    <p style="margin:0 0 24px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#334155;line-height:1.6;">
        Bonjour <strong style="color:#0f172a;">{{ $athlete->coach?->name ?? 'Coach' }}</strong>,
    </p>
    <p style="margin:0 0 28px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#334155;line-height:1.6;">
        Nous vous informons que l'inscription de votre athlète a été
        <strong style="color:#dc2626;">refusée</strong> par l'équipe technique de la Ligue.
    </p>

    {{-- Info card --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:{{ $athlete->rejection_reason ? '20px' : '28px' }};">
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
                        <td style="padding:12px 20px;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Club</span><br>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#334155;">{{ $athlete->club }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if($athlete->rejection_reason)
    {{-- Rejection reason box --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="background-color:#fee2e2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:6px;margin-bottom:28px;">
        <tr>
            <td style="padding:14px 18px;">
                <p style="margin:0 0 4px;font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#7f1d1d;">Motif du refus</p>
                <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#7f1d1d;line-height:1.6;">{{ $athlete->rejection_reason }}</p>
            </td>
        </tr>
    </table>
    @endif

    {{-- Contact note --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;margin-bottom:28px;">
        <tr>
            <td style="padding:14px 18px;">
                <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#64748b;line-height:1.6;">
                    Si vous pensez qu'il s'agit d'une erreur ou souhaitez des informations complémentaires,
                    contactez directement l'équipe technique de la Ligue.
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
