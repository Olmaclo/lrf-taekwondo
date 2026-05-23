<x-public-layout title="Vérifier mon inscription" description="Vérifiez le statut de votre inscription — Ligue de Fatick">

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    {{-- Header --}}
    <div style="background: #000; padding: 5.5rem 0 4.5rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 700px; height: 300px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(245,158,11,0.08) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: rgba(255,255,255,0.05);"></div>
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative; text-align: center;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 16px; margin-bottom: 1.5rem;">
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Athlètes</span>
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
            </div>
            <h1 style="font-size: clamp(2rem, 5vw, 4.5rem); font-weight: 700; color: #fff; line-height: 1; letter-spacing: -0.03em; margin: 0 0 1.25rem; font-family: 'Space Grotesk', sans-serif;">Vérifier mon inscription</h1>
            <p style="color: rgba(255,255,255,0.3); font-size: 0.95rem; max-width: 32rem; margin: 0 auto;">Recherchez par numéro de licence ou nom pour consulter votre statut d'inscription.</p>
        </div>
    </div>

    <div style="max-width: 600px; margin: 0 auto; padding: 4rem 2rem 6rem;">

        {{-- Search form --}}
        <form method="GET" style="margin-bottom: 2.5rem;">
            <label style="display: block; color: rgba(255,255,255,0.5); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px;">
                Numéro de licence ou Nom complet
            </label>
            <div style="display: flex; gap: 8px;">
                <div style="position: relative; flex: 1;">
                    <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #525252;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Ex: LIC-2024-001 ou Amadou Diallo"
                           style="width: 100%; padding: 13px 14px 13px 42px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); border-radius: 8px; color: #fff; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='rgba(255,255,255,0.12)'"
                           autofocus>
                </div>
                <button type="submit"
                        style="padding: 13px 24px; background: #f59e0b; color: #000; font-weight: 700; font-size: 0.875rem; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s; white-space: nowrap; letter-spacing: 0.03em;"
                        onmouseover="this.style.background='#fbbf24'" onmouseout="this.style.background='#f59e0b'">
                    Vérifier
                </button>
            </div>
        </form>

        {{-- Results --}}
        @if(request()->filled('q'))
            @if($athlete)
            <div style="border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; overflow: hidden; background: #0a0a0a;">
                {{-- Status top bar --}}
                <div style="height: 3px; background: {{ $athlete->registration_status === 'validated' ? '#22c55e' : ($athlete->registration_status === 'rejected' ? '#ef4444' : '#f59e0b') }};"></div>

                <div style="padding: 2rem;">
                    {{-- Identity --}}
                    <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.07);">
                        <img src="{{ $athlete->photo_url }}" alt="{{ $athlete->full_name }}"
                             style="width: 72px; height: 72px; border-radius: 10px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1); flex-shrink: 0;">
                        <div>
                            <h2 style="font-size: 1.4rem; font-weight: 900; color: #fff; margin: 0 0 0.5rem;">{{ $athlete->full_name }}</h2>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                                @php
                                    $statusBg = $athlete->registration_status === 'validated' ? 'rgba(34,197,94,0.1)' : ($athlete->registration_status === 'rejected' ? 'rgba(239,68,68,0.1)' : 'rgba(245,158,11,0.1)');
                                    $statusColor = $athlete->registration_status === 'validated' ? '#4ade80' : ($athlete->registration_status === 'rejected' ? '#f87171' : '#f59e0b');
                                    $statusBorder = $athlete->registration_status === 'validated' ? 'rgba(34,197,94,0.3)' : ($athlete->registration_status === 'rejected' ? 'rgba(239,68,68,0.3)' : 'rgba(245,158,11,0.3)');
                                @endphp
                                <span style="display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 0.7rem; font-weight: 700; background: {{ $statusBg }}; color: {{ $statusColor }}; border: 1px solid {{ $statusBorder }};">
                                    {{ $athlete->registration_status_label }}
                                </span>
                                @if($athlete->license_number)
                                <span style="display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 0.7rem; font-weight: 600; background: rgba(255,255,255,0.05); color: #737373; border: 1px solid rgba(255,255,255,0.08); font-family: monospace;">
                                    {{ $athlete->license_number }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Info grid --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 1.5rem;">
                        @foreach([
                            ['Club',       $athlete->club],
                            ['Événement',  $athlete->event?->name],
                            ['Coach',      $athlete->coach?->name],
                            ['Catégorie',  $athlete->category_label],
                            ['Naissance',  $athlete->birth_date?->format('d/m/Y')],
                            ['Inscrit le', $athlete->created_at->format('d/m/Y')],
                        ] as [$label, $value])
                        @if($value)
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 12px 14px;">
                            <div style="font-size: 0.65rem; color: #525252; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.08em;">{{ $label }}</div>
                            <div style="font-size: 0.875rem; color: #fff; font-weight: 600;">{{ $value }}</div>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    {{-- Payment --}}
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 14px 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <div style="font-size: 0.65rem; color: #525252; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.08em;">Paiement</div>
                            <div style="font-size: 0.9rem; color: #fff; font-weight: 700;">{{ $athlete->payment_status_label }}</div>
                        </div>
                        @if($athlete->payment_amount)
                        <div style="text-align: right;">
                            <div style="font-size: 0.65rem; color: #525252; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.08em;">Montant</div>
                            <div style="font-size: 1.2rem; color: #f59e0b; font-weight: 900;">{{ number_format($athlete->payment_amount, 0, ',', ' ') }} FCFA</div>
                        </div>
                        @endif
                    </div>

                    @if($athlete->registration_status === 'rejected' && $athlete->rejection_reason)
                    <div style="margin-top: 1rem; padding: 14px 16px; background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.2); border-radius: 8px;">
                        <div style="font-size: 0.65rem; color: #f87171; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px;">Motif de rejet</div>
                        <p style="color: #fca5a5; font-size: 0.875rem; margin: 0;">{{ $athlete->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @else
            <div style="border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 3rem 2rem; text-align: center; background: #0a0a0a;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
                <h3 style="color: #737373; font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Aucun résultat</h3>
                <p style="color: #525252; font-size: 0.875rem; margin-bottom: 1.5rem;">
                    Aucun athlète trouvé pour « {{ request('q') }} ».
                </p>
                <a href="{{ route('public.inscription') }}"
                   style="display: inline-flex; align-items: center; gap: 8px; background: #f59e0b; color: #000; font-weight: 700; font-size: 0.8rem; padding: 10px 20px; border-radius: 7px; text-decoration: none; letter-spacing: 0.03em;">
                    S'inscrire maintenant
                </a>
            </div>
            @endif
        @else
        <div style="border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 3rem 2rem; text-align: center; background: rgba(255,255,255,0.01);">
            <div style="font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.3;">🏷️</div>
            <p style="color: #333; font-size: 0.875rem;">Entrez votre nom ou numéro de licence pour commencer.</p>
        </div>
        @endif

        <p style="text-align: center; color: #333; font-size: 0.8rem; margin-top: 2rem;">
            Pas encore inscrit ?
            <a href="{{ route('public.inscription') }}" style="color: #f59e0b; text-decoration: none; font-weight: 600;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                Inscrire un athlète →
            </a>
        </p>
    </div>
</div>

</x-public-layout>
