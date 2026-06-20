@props([
    'title'     => '',
    'preheader' => '',
    'accent'    => 'gold',   // gold | green | red | blue
])
@php
$colors = [
    'gold'  => ['stripe' => '#f59e0b', 'badge_bg' => '#fef3c7', 'badge_text' => '#92400e', 'btn' => '#f59e0b', 'btn_text' => '#000000', 'icon_bg' => '#fef9c3'],
    'green' => ['stripe' => '#22c55e', 'badge_bg' => '#dcfce7', 'badge_text' => '#14532d', 'btn' => '#16a34a', 'btn_text' => '#ffffff', 'icon_bg' => '#dcfce7'],
    'red'   => ['stripe' => '#ef4444', 'badge_bg' => '#fee2e2', 'badge_text' => '#7f1d1d', 'btn' => '#dc2626', 'btn_text' => '#ffffff', 'icon_bg' => '#fee2e2'],
    'blue'  => ['stripe' => '#3b82f6', 'badge_bg' => '#dbeafe', 'badge_text' => '#1e3a8a', 'btn' => '#2563eb', 'btn_text' => '#ffffff', 'icon_bg' => '#dbeafe'],
];
$c = $colors[$accent] ?? $colors['gold'];
$logoUrl = rtrim(config('app.url'), '/') . '/images/logo.png';
$siteUrl = config('app.url');
$year    = date('Y');
@endphp
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }}</title>
    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #edf2f7; }
        a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; }
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; }
            .stack-col { display: block !important; width: 100% !important; }
            .mobile-padding { padding: 24px 20px !important; }
            .mobile-center { text-align: center !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#edf2f7;">

{{-- Preheader hidden text --}}
@if($preheader)
<div style="display:none;font-size:1px;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    {{ $preheader }}
</div>
@endif

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:#edf2f7;">
<tr><td align="center" style="padding:32px 16px 48px;">

    {{-- ── Email card ─────────────────────────────────── --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" class="email-container"
           style="width:600px;max-width:600px;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.10);">

        {{-- ── HEADER ──────────────────────────────────── --}}
        <tr>
            <td style="background-color:#0a1628;padding:0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td align="center" style="padding:36px 40px 28px;">
                            {{-- Logo --}}
                            <img src="{{ $logoUrl }}"
                                 alt="Ligue Régionale de Taekwondo de Fatick"
                                 width="110" height="110"
                                 style="display:block;width:110px;height:110px;object-fit:contain;border-radius:50%;border:3px solid {{ $c['stripe'] }};">
                            {{-- Org name --}}
                            <div style="margin-top:14px;font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:rgba(255,255,255,0.5);">
                                Ligue Régionale de Taekwondo de Fatick
                            </div>
                        </td>
                    </tr>
                    {{-- Gold accent stripe --}}
                    <tr>
                        <td style="height:4px;background:linear-gradient(90deg, transparent 0%, {{ $c['stripe'] }} 20%, {{ $c['stripe'] }} 80%, transparent 100%);font-size:0;line-height:0;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- ── BODY ────────────────────────────────────── --}}
        <tr>
            <td style="background-color:#ffffff;padding:40px 48px 36px;" class="mobile-padding">
                {{ $slot }}
            </td>
        </tr>

        {{-- ── DIVIDER ──────────────────────────────────── --}}
        <tr>
            <td style="background-color:#ffffff;padding:0 48px;">
                <div style="height:1px;background-color:#e2e8f0;font-size:0;line-height:0;">&nbsp;</div>
            </td>
        </tr>

        {{-- ── FOOTER ───────────────────────────────────── --}}
        <tr>
            <td style="background-color:#0a1628;padding:28px 40px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td align="center">
                            <p style="margin:0 0 6px;font-family:Arial,Helvetica,sans-serif;font-size:13px;font-weight:700;color:#f59e0b;letter-spacing:0.04em;">
                                Ligue Régionale de Taekwondo de Fatick
                            </p>
                            <p style="margin:0 0 10px;font-family:Arial,Helvetica,sans-serif;font-size:11px;color:rgba(255,255,255,0.4);letter-spacing:0.06em;">
                                <a href="{{ $siteUrl }}" style="color:rgba(255,255,255,0.4);text-decoration:underline;">lrftaekwondo.com</a>
                            </p>
                            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:10px;color:rgba(255,255,255,0.2);">
                                © {{ $year }} · Cet email a été envoyé automatiquement, merci de ne pas y répondre.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
    {{-- ── end card ─── --}}

</td></tr>
</table>

</body>
</html>
