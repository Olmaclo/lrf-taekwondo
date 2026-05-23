<x-public-layout :title="'Liste officielle — ' . $event->name" :description="'Liste officielle des athlètes inscrits à ' . $event->name">

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    {{-- Hero --}}
    <div style="background: #000; padding: 4.5rem 0; position: relative; overflow: hidden; border-bottom: 1px solid rgba(255,255,255,0.06);">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 800px; height: 300px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(245,158,11,0.08) 0%, transparent 70%); pointer-events: none;"></div>
        <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-20" style="position: relative;">

            <a href="{{ route('public.event-detail', $event->slug) }}"
               style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.3); font-size: 0.72rem; text-decoration: none; margin-bottom: 2.5rem; transition: color 0.2s; letter-spacing: 0.08em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ $event->name }}
            </a>

            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 1.5rem;">
                <span style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.4); letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">01</span>
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Liste officielle</span>
            </div>

            <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 2rem;">
                <div>
                    <h1 style="font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 700; color: #fff; line-height: 1.05; letter-spacing: -0.03em; margin: 0 0 0.75rem; font-family: 'Space Grotesk', sans-serif;">
                        Athlètes inscrits
                    </h1>
                    <p style="color: rgba(255,255,255,0.3); font-size: 0.875rem; margin: 0;">
                        {{ $grouped->count() }} catégorie(s) &nbsp;·&nbsp; {{ $grouped->flatten()->count() }} athlète(s) validé(s)
                    </p>
                </div>
                <a href="{{ route('public.draws', $event->slug) }}"
                   style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.4); font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; text-decoration: none; border: 1px solid rgba(255,255,255,0.1); padding: 9px 18px; transition: all 0.2s;"
                   onmouseover="this.style.color='#f59e0b'; this.style.borderColor='rgba(245,158,11,0.4)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.1)'">
                    Voir les tirages
                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Content --}}
    @if($grouped->isEmpty())
    <div style="padding: 6rem 0; text-align: center;">
        <svg style="width: 48px; height: 48px; color: rgba(255,255,255,0.1); margin: 0 auto 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        <p style="color: rgba(255,255,255,0.25); font-size: 0.875rem;">Aucun athlète validé pour cet événement.</p>
    </div>
    @else
    <div style="padding: 5rem 0 7rem;">
        <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-20">
            @php $catNum = 0; @endphp
            @foreach($grouped as $key => $athletes)
                @php
                    $catNum++;
                    [$ageCategory, $gender, $weightCategory] = explode('||', $key);
                    $genderLabel = \App\Models\Athlete::genderLabel($gender, $ageCategory);
                    $genderColor = $gender === 'M' ? '#60a5fa' : '#f472b6';
                @endphp

                <div style="margin-bottom: 5rem;">
                    {{-- Category header --}}
                    <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.07);">
                        <div style="width: 2.75rem; height: 2.75rem; background: rgba(245,158,11,0.06); border: 1px solid rgba(245,158,11,0.2); display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 0.9rem; color: #f59e0b; flex-shrink: 0;">
                            {{ str_pad($catNum, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <h2 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin: 0; font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em;">
                                {{ $ageCategory }}
                                <span style="color: {{ $genderColor }}; font-weight: 600;">{{ $genderLabel }}</span>
                                <span style="color: rgba(255,255,255,0.5); font-weight: 400;"> — {{ $weightCategory }}</span>
                            </h2>
                            <p style="font-size: 0.65rem; color: rgba(255,255,255,0.25); margin: 4px 0 0; text-transform: uppercase; letter-spacing: 0.14em;">{{ $athletes->count() }} athlète(s)</p>
                        </div>
                        <div style="font-size: 2rem; font-weight: 700; color: rgba(245,158,11,0.12); font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.04em; flex-shrink: 0;">
                            {{ $athletes->count() }}
                        </div>
                    </div>

                    {{-- Athletes table --}}
                    <div style="overflow-x: auto; border: 1px solid rgba(255,255,255,0.06);">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 600px;">
                            <thead>
                                <tr style="background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.07);">
                                    <th style="padding: 11px 16px; text-align: left; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; white-space: nowrap; width: 52px;">N°</th>
                                    <th style="padding: 11px 16px; text-align: left; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em;">Nom complet</th>
                                    <th style="padding: 11px 16px; text-align: left; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em;">Club</th>
                                    <th style="padding: 11px 16px; text-align: left; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; white-space: nowrap;">N° Licence</th>
                                    <th style="padding: 11px 16px; text-align: left; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; white-space: nowrap;">Poids</th>
                                    <th style="padding: 11px 16px; text-align: left; color: rgba(255,255,255,0.22); font-size: 0.58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em;">Coach</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($athletes as $i => $athlete)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.15s;"
                                    onmouseover="this.style.background='rgba(245,158,11,0.025)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 13px 16px; color: rgba(255,255,255,0.2); font-size: 0.7rem; font-weight: 700; font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.05em;">
                                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td style="padding: 13px 16px; color: #fff; font-weight: 600; white-space: nowrap;">{{ $athlete->full_name }}</td>
                                    <td style="padding: 13px 16px; color: rgba(255,255,255,0.45);">{{ $athlete->club ?? '—' }}</td>
                                    <td style="padding: 13px 16px; color: rgba(255,255,255,0.3); font-family: monospace; font-size: 0.82rem; white-space: nowrap;">{{ $athlete->license_number ?? '—' }}</td>
                                    <td style="padding: 13px 16px; color: rgba(255,255,255,0.45); white-space: nowrap;">{{ $athlete->weight }} kg</td>
                                    <td style="padding: 13px 16px; color: rgba(255,255,255,0.3); white-space: nowrap;">{{ $athlete->coach?->name ?? '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

</x-public-layout>
