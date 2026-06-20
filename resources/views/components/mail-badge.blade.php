@props(['accent' => 'gold', 'label' => ''])
@php
$colors = [
    'gold'  => ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fde68a'],
    'green' => ['bg' => '#dcfce7', 'text' => '#14532d', 'border' => '#bbf7d0'],
    'red'   => ['bg' => '#fee2e2', 'text' => '#7f1d1d', 'border' => '#fecaca'],
    'blue'  => ['bg' => '#dbeafe', 'text' => '#1e3a8a', 'border' => '#bfdbfe'],
];
$c = $colors[$accent] ?? $colors['gold'];
@endphp
<div style="text-align:center;margin-bottom:24px;">
    <span style="display:inline-block;background-color:{{ $c['bg'] }};color:{{ $c['text'] }};border:1px solid {{ $c['border'] }};padding:6px 20px;border-radius:20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">
        {{ $label }}
    </span>
</div>
