<x-public-layout title="Inscription athlète" description="Inscrivez votre athlète aux compétitions — Ligue de Fatick">

<style>
@media (max-width: 600px) {
    .form-grid-2 { grid-template-columns: 1fr !important; }
    .modal-form-grid { grid-template-columns: 1fr !important; }
}
</style>

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    {{-- Header --}}
    <div style="background: #000; padding: 4.5rem 0 3.5rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 700px; height: 260px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(245,158,11,0.07) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: rgba(255,255,255,0.05);"></div>
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative;">
            <a href="{{ route('public.events') }}" style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.3); font-size: 0.75rem; text-decoration: none; margin-bottom: 1.75rem; transition: color 0.2s; letter-spacing: 0.06em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Retour aux événements
            </a>
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 1.25rem;">
                <span style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.4); letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">—</span>
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Inscription</span>
            </div>
            <h1 style="font-size: clamp(2rem, 5vw, 4rem); font-weight: 700; color: #fff; line-height: 1; letter-spacing: -0.03em; margin: 0 0 1rem; font-family: 'Space Grotesk', sans-serif;">Inscrire un athlète</h1>
            <p style="color: rgba(255,255,255,0.3); font-size: 0.9rem; max-width: 38rem;">Remplissez le formulaire ci-dessous pour soumettre l'inscription de votre athlète — elle sera validée par l'équipe technique.</p>
            <div style="display: inline-flex; align-items: center; gap: 10px; margin-top: 1.25rem; padding: 9px 16px; background: rgba(34,197,94,0.07); border: 1px solid rgba(34,197,94,0.25); border-radius: 8px;">
                <img src="{{ auth()->user()->avatar_url }}" alt="" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                <div>
                    <span style="color: rgba(34,197,94,0.9); font-size: 0.72rem; font-weight: 700; letter-spacing: 0.04em;">Connecté en tant que coach · </span>
                    <span style="color: rgba(255,255,255,0.7); font-size: 0.72rem; font-weight: 600;">{{ auth()->user()->name }}</span>
                    @if(auth()->user()->club)
                    <span style="color: rgba(255,255,255,0.35); font-size: 0.72rem;"> — {{ auth()->user()->club }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div style="max-width: 720px; margin: 0 auto; padding: 3rem 2rem 6rem;">

        @if(session('last_inscription'))
        @php $ins = session('last_inscription'); @endphp
        <div style="background: rgba(34,197,94,0.07); border: 1px solid rgba(34,197,94,0.25); border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 2rem; display: flex; align-items: flex-start; gap: 14px;">
            <div style="width: 36px; height: 36px; background: rgba(34,197,94,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                <svg style="width: 18px; height: 18px; color: #4ade80;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div style="flex: 1; min-width: 0;">
                <p style="color: #4ade80; font-weight: 700; font-size: 0.875rem; margin: 0 0 4px;">Inscription soumise avec succès</p>
                <p style="color: rgba(255,255,255,0.6); font-size: 0.82rem; margin: 0 0 10px; line-height: 1.5;">
                    <span style="color: #fff; font-weight: 600;">{{ $ins['name'] }}</span>
                    &nbsp;·&nbsp; Dossier <span style="color: #f59e0b; font-weight: 700;">#{{ $ins['id'] }}</span>
                    &nbsp;·&nbsp; {{ $ins['category'] }}
                    @if($ins['license']) &nbsp;·&nbsp; Licence : {{ $ins['license'] }} @endif
                </p>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('public.verify', ['q' => $ins['license'] ?? $ins['name']]) }}"
                       style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.3); border-radius: 6px; color: #4ade80; font-size: 0.75rem; font-weight: 600; text-decoration: none; transition: background 0.2s;"
                       onmouseover="this.style.background='rgba(34,197,94,0.2)'" onmouseout="this.style.background='rgba(34,197,94,0.12)'">
                        <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Vérifier l'inscription
                    </a>
                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25); border-radius: 6px; color: rgba(245,158,11,0.8); font-size: 0.75rem; font-weight: 600;">
                        <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Formulaire prêt pour le prochain athlète
                    </span>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div style="background: rgba(239,68,68,0.07); border: 1px solid rgba(239,68,68,0.25); border-radius: 10px; padding: 1.25rem 1.5rem; margin-bottom: 2rem;">
            <p style="color: #f87171; font-weight: 700; font-size: 0.875rem; margin: 0 0 0.75rem;">Erreurs à corriger :</p>
            <ul style="margin: 0; padding-left: 1.25rem; color: #fca5a5; font-size: 0.8rem; line-height: 1.8;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Step indicator --}}
        <div id="step-indicator" style="display: flex; align-items: center; margin-bottom: 3rem;">
            @foreach([1 => 'Identité', 2 => 'Sport', 3 => 'Événement'] as $num => $label)
            <div style="display: flex; align-items: center; {{ $num < 3 ? 'flex: 1;' : '' }}">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 6px;">
                    <div id="step-dot-{{ $num }}" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; transition: all 0.3s; background: #f59e0b; color: #000; border: 2px solid #f59e0b;">{{ $num }}</div>
                    <span id="step-label-{{ $num }}" style="font-size: 0.65rem; font-weight: 600; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.1em; white-space: nowrap;">{{ $label }}</span>
                </div>
                @if($num < 3)
                <div id="step-line-{{ $num }}" style="flex: 1; height: 2px; background: #f59e0b; margin: 0 8px; margin-bottom: 20px; transition: background 0.3s;"></div>
                @endif
            </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('public.inscription.store') }}" enctype="multipart/form-data" id="inscription-form">
            @csrf

            {{-- STEP 1 --}}
            <div id="step-1" style="display: block;">
                <div style="background: #0a0a0a; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 2rem; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1rem; font-weight: 800; color: #fff; margin: 0 0 1.5rem; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 26px; height: 26px; background: rgba(245,158,11,0.15); color: #f59e0b; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; flex-shrink: 0;">1</span>
                        Informations personnelles
                    </h2>
                    <div class="form-grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        @foreach([
                            ['name' => 'first_name', 'label' => 'Prénom', 'type' => 'text',   'required' => true,  'placeholder' => ''],
                            ['name' => 'last_name',  'label' => 'Nom',    'type' => 'text',   'required' => true,  'placeholder' => ''],
                            ['name' => 'birth_date', 'label' => 'Date de naissance', 'type' => 'date', 'required' => true, 'placeholder' => ''],
                            ['name' => 'birth_place','label' => 'Lieu de naissance', 'type' => 'text', 'required' => false,'placeholder' => ''],
                            ['name' => 'nationality','label' => 'Nationalité',       'type' => 'text', 'required' => false,'placeholder' => 'Sénégalais(e)'],
                        ] as $field)
                        <div>
                            <label style="display: block; color: rgba(255,255,255,0.45); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px;">
                                {{ $field['label'] }}@if($field['required'])<span style="color: #f59e0b;"> *</span>@endif
                            </label>
                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                                   value="{{ old($field['name'], $field['placeholder'] ?? '') }}"
                                   placeholder="{{ $field['placeholder'] ?? '' }}"
                                   @if($field['required']) required @endif
                                   style="width: 100%; padding: 11px 14px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; outline: none; transition: border-color 0.2s; box-sizing: border-box;"
                                   onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                        </div>
                        @endforeach
                        <div>
                            <label style="display: block; color: rgba(255,255,255,0.45); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px;">
                                Genre <span style="color: #f59e0b;">*</span>
                            </label>
                            <select name="gender" required style="width: 100%; padding: 11px 14px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; outline: none; cursor: pointer; box-sizing: border-box;" onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                                <option value="" style="background:#111;">Sélectionner…</option>
                                <option value="M" {{ old('gender')==='M' ? 'selected' : '' }} style="background:#111;">Masculin</option>
                                <option value="F" {{ old('gender')==='F' ? 'selected' : '' }} style="background:#111;">Féminin</option>
                            </select>
                        </div>
                    </div>
                    {{-- Photo upload --}}
                    <div style="margin-top: 14px;">
                        <label style="display: block; color: rgba(255,255,255,0.45); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px;">Photo (optionnel)</label>
                        <div onclick="document.getElementById('photo-input').click()"
                             style="border: 1px dashed rgba(255,255,255,0.15); border-radius: 8px; padding: 1.5rem; text-align: center; cursor: pointer; transition: border-color 0.2s; background: rgba(255,255,255,0.02);"
                             onmouseover="this.style.borderColor='#f59e0b'" onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'">
                            <svg style="width: 28px; height: 28px; color: #333; margin: 0 auto 8px; display: block;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <p style="color: #525252; font-size: 0.8rem; margin: 0;">Cliquer pour choisir une image</p>
                            <p style="color: #333; font-size: 0.7rem; margin: 4px 0 0;">JPEG, PNG — max 2 Mo</p>
                        </div>
                        <input type="file" id="photo-input" name="photo" accept="image/*" style="display: none;" onchange="document.getElementById('photo-name').textContent = this.files[0]?.name || ''">
                        <p id="photo-name" style="color: #f59e0b; font-size: 0.75rem; margin-top: 6px;"></p>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="button" onclick="goStep(2); tkdUpdateCategories();"
                            style="display: inline-flex; align-items: center; gap: 8px; background: #f59e0b; color: #000; font-weight: 700; font-size: 0.875rem; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s; letter-spacing: 0.03em;"
                            onmouseover="this.style.background='#fbbf24'" onmouseout="this.style.background='#f59e0b'">
                        Suivant <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>
                </div>
            </div>

            {{-- STEP 2 --}}
            <div id="step-2" style="display: none;">
                <div style="background: #0a0a0a; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 2rem; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1rem; font-weight: 800; color: #fff; margin: 0 0 1.5rem; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 26px; height: 26px; background: rgba(245,158,11,0.15); color: #f59e0b; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; flex-shrink: 0;">2</span>
                        Informations sportives
                    </h2>

                    @php $fStyle = 'width:100%;padding:11px 14px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:8px;color:#fff;font-size:0.875rem;outline:none;transition:border-color 0.2s;box-sizing:border-box;'; @endphp
                    @php $lStyle = 'display:block;color:rgba(255,255,255,0.45);font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;'; @endphp

                    <div class="form-grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">

                        {{-- Club --}}
                        <div>
                            <label style="{{ $lStyle }}">Club <span style="color:#f59e0b;">*</span></label>
                            <input type="text" name="club" value="{{ old('club') }}"
                                   placeholder="Nom de votre club" required
                                   style="{{ $fStyle }}"
                                   onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                        </div>

                        {{-- N° de licence --}}
                        <div>
                            <label style="{{ $lStyle }}">N° de licence</label>
                            <input type="text" name="license_number" value="{{ old('license_number') }}"
                                   placeholder="Ex: LIC-2024-001"
                                   style="{{ $fStyle }}"
                                   onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                        </div>

                        {{-- Catégorie d'âge (calculée) --}}
                        <div>
                            <label style="{{ $lStyle }}">
                                Catégorie d'âge
                                <span style="font-size:0.6rem;color:#525252;font-weight:400;text-transform:none;letter-spacing:0;margin-left:4px;">(calculée automatiquement)</span>
                            </label>
                            <input type="text" id="tkd-age-display"
                                   value="{{ old('age_category', '—') }}" readonly tabindex="-1"
                                   style="{{ $fStyle }}cursor:default;color:#94a3b8;user-select:none;">
                            <input type="hidden" name="age_category" id="tkd-age-input" value="{{ old('age_category') }}">
                        </div>

                        {{-- Catégorie de poids (dropdown) --}}
                        <div>
                            <label style="{{ $lStyle }}">Catégorie de poids <span style="color:#f59e0b;">*</span></label>
                            <select name="weight_category" id="tkd-weight-select" required
                                    style="{{ $fStyle }}cursor:pointer;"
                                    onchange="tkdUpdatePreview()"
                                    onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                                <option value="" style="background:#111;">— sélectionner une catégorie —</option>
                                @if(old('weight_category'))
                                    <option value="{{ old('weight_category') }}" selected style="background:#111;">{{ old('weight_category') }}</option>
                                @endif
                            </select>
                            <p id="tkd-weight-hint" style="margin:5px 0 0;font-size:0.7rem;color:#525252;display:none;">Renseignez la date de naissance et le genre pour voir les catégories disponibles.</p>
                        </div>

                    </div>

                    {{-- Category preview badge --}}
                    <div id="tkd-preview" style="display:none;margin-top:16px;background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.2);border-radius:8px;padding:14px 16px;flex-wrap:wrap;align-items:center;gap:14px;">
                        <div style="display:flex;flex-direction:column;gap:2px;">
                            <span style="color:rgba(245,158,11,0.6);font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Catégorie d'âge</span>
                            <span id="tkd-prev-age" style="color:#fff;font-weight:800;font-size:0.95rem;"></span>
                        </div>
                        <div style="width:1px;height:30px;background:rgba(245,158,11,0.2);flex-shrink:0;"></div>
                        <div style="display:flex;flex-direction:column;gap:2px;">
                            <span style="color:rgba(245,158,11,0.6);font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Genre</span>
                            <span id="tkd-prev-gender" style="color:#fff;font-weight:800;font-size:0.95rem;"></span>
                        </div>
                        <div style="width:1px;height:30px;background:rgba(245,158,11,0.2);flex-shrink:0;"></div>
                        <div style="display:flex;flex-direction:column;gap:2px;">
                            <span style="color:rgba(245,158,11,0.6);font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Catégorie de poids</span>
                            <span id="tkd-prev-weight" style="color:#f59e0b;font-weight:800;font-size:1.05rem;"></span>
                        </div>
                        <div style="margin-left:auto;display:flex;align-items:center;gap:6px;padding:5px 12px;background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.25);border-radius:20px;">
                            <svg style="width:12px;height:12px;color:#f59e0b;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span style="color:#f59e0b;font-size:0.7rem;font-weight:700;white-space:nowrap;">Catégorie confirmée</span>
                        </div>
                    </div>

                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <button type="button" onclick="goStep(1)" style="display: inline-flex; align-items: center; gap: 8px; background: transparent; color: rgba(255,255,255,0.4); font-weight: 600; font-size: 0.875rem; padding: 12px 20px; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; transition: color 0.2s, border-color 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.3)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.1)'">
                        ← Précédent
                    </button>
                    <button type="button" onclick="goStep(3)" style="display: inline-flex; align-items: center; gap: 8px; background: #f59e0b; color: #000; font-weight: 700; font-size: 0.875rem; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s; letter-spacing: 0.03em;" onmouseover="this.style.background='#fbbf24'" onmouseout="this.style.background='#f59e0b'">
                        Suivant <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>
                </div>
            </div>

            {{-- STEP 3 --}}
            {{-- Event deadlines passed as JSON for client-side guard --}}
            @php
            $eventDeadlines = $events->mapWithKeys(fn($e) => [
                $e->id => $e->registration_deadline?->toIso8601String()
            ])->toArray();
            @endphp

            <div id="step-3" style="display: none;">
                <div style="background: #0a0a0a; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 2rem; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1rem; font-weight: 800; color: #fff; margin: 0 0 1.5rem; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 26px; height: 26px; background: rgba(245,158,11,0.15); color: #f59e0b; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; flex-shrink: 0;">3</span>
                        Choix de l'événement
                    </h2>
                    <div style="display: flex; flex-direction: column; gap: 14px;">

                        {{-- Event select --}}
                        <div>
                            <label style="display: block; color: rgba(255,255,255,0.45); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px;">
                                Événement <span style="color: #f59e0b;">*</span>
                            </label>
                            @if($events->count())
                            <select name="event_id" id="event-select" required
                                    onchange="checkEventRegistration()"
                                    style="width: 100%; padding: 11px 14px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; outline: none; cursor: pointer; box-sizing: border-box;"
                                    onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                                <option value="" style="background:#111;">Sélectionner un événement…</option>
                                @foreach($events as $event)
                                <option value="{{ $event->id }}"
                                        data-deadline="{{ $event->registration_deadline?->toIso8601String() ?? '' }}"
                                        {{ old('event_id') == $event->id || request('event_id') == $event->id ? 'selected' : '' }}
                                        style="background:#111;">
                                    {{ $event->name }} — {{ $event->start_date->format('d M Y') }}@if($event->registration_fee) ({{ number_format($event->registration_fee, 0, ',', ' ') }} FCFA)@endif
                                </option>
                                @endforeach
                            </select>
                            @else
                            <div style="padding: 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #525252; font-size: 0.875rem; text-align: center;">
                                Aucun événement ouvert aux inscriptions pour le moment.
                            </div>
                            @endif
                        </div>

                        {{-- Registration status notice (dynamic) --}}
                        <div id="event-status-notice" style="display:none;"></div>

                        {{-- Coach select --}}
                        <div>
                            <label style="display: block; color: rgba(255,255,255,0.45); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px;">
                                Coach référent
                            </label>
                            <div style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(34,197,94,0.2); border-radius: 8px;">
                                <img src="{{ auth()->user()->avatar_url }}" alt="" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                                <div style="min-width: 0;">
                                    <div style="color: #fff; font-size: 0.875rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->name }}</div>
                                    @if(auth()->user()->club)
                                    <div style="color: rgba(255,255,255,0.35); font-size: 0.75rem;">{{ auth()->user()->club }}</div>
                                    @endif
                                </div>
                                <svg style="width: 14px; height: 14px; color: rgba(34,197,94,0.7); margin-left: auto; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <input type="hidden" name="coach_id" value="{{ auth()->user()->id }}">
                        </div>

                        <div style="background: rgba(245,158,11,0.05); border: 1px solid rgba(245,158,11,0.15); border-radius: 8px; padding: 14px 16px; display: flex; gap: 10px; align-items: flex-start;">
                            <svg style="width: 16px; height: 16px; color: #f59e0b; flex-shrink: 0; margin-top: 1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                            <p style="color: #a3a3a3; font-size: 0.8rem; line-height: 1.6; margin: 0;">La catégorie d'âge et de poids sera calculée automatiquement. Votre inscription sera soumise pour validation par notre équipe.</p>
                        </div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <button type="button" onclick="goStep(2)" style="display: inline-flex; align-items: center; gap: 8px; background: transparent; color: rgba(255,255,255,0.4); font-weight: 600; font-size: 0.875rem; padding: 12px 20px; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.3)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.1)'">
                        ← Précédent
                    </button>
                    <button type="submit" id="submit-btn" style="display: inline-flex; align-items: center; gap: 8px; background: #f59e0b; color: #000; font-weight: 700; font-size: 0.875rem; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s; letter-spacing: 0.03em;" onmouseover="this.style.background='#fbbf24'" onmouseout="this.style.background='#f59e0b'">
                        Soumettre l'inscription
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
var currentStep = 1;
function goStep(n) {
    document.getElementById('step-' + currentStep).style.display = 'none';
    document.getElementById('step-' + n).style.display = 'block';
    currentStep = n;
    for (var i = 1; i <= 3; i++) {
        var dot = document.getElementById('step-dot-' + i);
        var label = document.getElementById('step-label-' + i);
        var active = i <= n;
        dot.style.background = active ? '#f59e0b' : 'rgba(255,255,255,0.05)';
        dot.style.color = active ? '#000' : '#525252';
        dot.style.borderColor = active ? '#f59e0b' : 'rgba(255,255,255,0.1)';
        label.style.color = active ? '#f59e0b' : '#525252';
        if (i < 3) {
            var line = document.getElementById('step-line-' + i);
            if (line) line.style.background = i < n ? '#f59e0b' : 'rgba(255,255,255,0.1)';
        }
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
document.getElementById('inscription-form').addEventListener('submit', function() {
    var btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.style.opacity = '0.7';
    btn.textContent = 'Envoi en cours…';
});

// ── Taekwondo category calculator ─────────────────────────────────────────────
var TKD = {
    ages: {
        'Benjamin': [8, 9],
        'Minime':   [10, 11],
        'Cadet':    [12, 14],
        'Junior':   [15, 17],
        'Senior':   [18, 99]
    },
    weights: {
        'Benjamin': {
            'M': {'-21kg':[18,21],'-24kg':[21,24],'-27kg':[24,27],'-30kg':[27,30],'-33kg':[30,33],'-37kg':[33,37],'-41kg':[37,41],'-45kg':[41,45],'-49kg':[45,49],'+49kg':[49,999]},
            'F': {'-17kg':[15,17],'-20kg':[17,20],'-23kg':[20,23],'-26kg':[23,26],'-29kg':[26,29],'-33kg':[29,33],'-37kg':[33,37],'-41kg':[37,41],'-44kg':[41,44],'+44kg':[44,999]}
        },
        'Minime': {
            'M': {'-27kg':[24,27],'-30kg':[27,30],'-33kg':[30,33],'-37kg':[33,37],'-41kg':[37,41],'-45kg':[41,45],'-49kg':[45,49],'-53kg':[49,53],'-57kg':[53,57],'+57kg':[57,999]},
            'F': {'-23kg':[20,23],'-26kg':[23,26],'-29kg':[26,29],'-33kg':[29,33],'-37kg':[33,37],'-41kg':[37,41],'-44kg':[41,44],'-47kg':[44,47],'-51kg':[47,51],'+51kg':[51,999]}
        },
        'Cadet': {
            'M': {'-33kg':[30,33],'-37kg':[33,37],'-41kg':[37,41],'-45kg':[41,45],'-49kg':[45,49],'-53kg':[49,53],'-57kg':[53,57],'-61kg':[57,61],'-65kg':[61,65],'+65kg':[65,999]},
            'F': {'-29kg':[26,29],'-33kg':[29,33],'-37kg':[33,37],'-41kg':[37,41],'-44kg':[41,44],'-47kg':[44,47],'-51kg':[47,51],'-55kg':[51,55],'-59kg':[55,59],'+59kg':[59,999]}
        },
        'Junior': {
            'M': {'-45kg':[42,45],'-48kg':[45,48],'-51kg':[48,51],'-55kg':[51,55],'-59kg':[55,59],'-63kg':[59,63],'-68kg':[63,68],'-73kg':[68,73],'-78kg':[73,78],'+78kg':[78,999]},
            'F': {'-42kg':[38,42],'-44kg':[42,44],'-46kg':[44,46],'-49kg':[46,49],'-52kg':[49,52],'-55kg':[52,55],'-59kg':[55,59],'-63kg':[59,63],'-68kg':[63,68],'+68kg':[68,999]}
        },
        'Senior': {
            'M': {'-54kg':[50,54],'-58kg':[54,58],'-63kg':[58,63],'-68kg':[63,68],'-74kg':[68,74],'-80kg':[74,80],'-87kg':[80,87],'+87kg':[87,999]},
            'F': {'-46kg':[42,46],'-49kg':[46,49],'-53kg':[49,53],'-57kg':[53,57],'-62kg':[57,62],'-67kg':[62,67],'-73kg':[67,73],'+73kg':[73,999]}
        }
    }
};

function tkdComputeAge(birthDateStr) {
    if (!birthDateStr) return null;
    var bd = new Date(birthDateStr);
    var today = new Date();
    var age = today.getFullYear() - bd.getFullYear();
    var m = today.getMonth() - bd.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < bd.getDate())) age--;
    return isNaN(age) ? null : age;
}

function tkdGetAgeCategory(age) {
    if (age === null) return null;
    for (var cat in TKD.ages) {
        var r = TKD.ages[cat];
        if (age >= r[0] && age <= r[1]) return cat;
    }
    return null;
}


function tkdUpdateCategories() {
    var birthDateStr = document.querySelector('[name=birth_date]').value;
    var gender       = document.querySelector('[name=gender]').value;

    var age         = tkdComputeAge(birthDateStr);
    var ageCategory = tkdGetAgeCategory(age);

    var ageDisplay = document.getElementById('tkd-age-display');
    var ageInput   = document.getElementById('tkd-age-input');

    if (ageCategory) {
        ageDisplay.value = ageCategory;
        ageDisplay.style.color = '#f59e0b';
        ageInput.value = ageCategory;
    } else if (age !== null && age < 8) {
        ageDisplay.value = 'Non éligible (âge minimum : 8 ans)';
        ageDisplay.style.color = '#ef4444';
        ageInput.value = '';
    } else {
        ageDisplay.value = '—';
        ageDisplay.style.color = '#94a3b8';
        ageInput.value = '';
    }

    var sel  = document.getElementById('tkd-weight-select');
    var hint = document.getElementById('tkd-weight-hint');
    var prev = sel.value;
    var cats = ageCategory && gender ? TKD.weights[ageCategory][gender] : null;

    sel.innerHTML = '<option value="" style="background:#111;">— sélectionner une catégorie —</option>';

    if (cats) {
        Object.keys(cats).forEach(function(name) {
            var opt = document.createElement('option');
            opt.value = name;
            opt.textContent = name;
            opt.style.background = '#111';
            sel.appendChild(opt);
        });

        if (prev && sel.querySelector('option[value="' + prev + '"]')) {
            sel.value = prev;
        }
        if (hint) hint.style.display = 'none';
    } else {
        if (hint) hint.style.display = 'block';
    }

    tkdUpdatePreview();
}

function tkdUpdatePreview() {
    var ageCategory    = document.getElementById('tkd-age-input').value;
    var gender         = document.querySelector('[name=gender]').value;
    var weightCategory = document.getElementById('tkd-weight-select').value;
    var preview        = document.getElementById('tkd-preview');

    if (ageCategory && gender && weightCategory) {
        document.getElementById('tkd-prev-age').textContent    = ageCategory;
        var isSenior = ageCategory.toLowerCase() === 'senior';
        document.getElementById('tkd-prev-gender').textContent  = gender === 'M' ? (isSenior ? 'Homme' : 'Garçon') : (isSenior ? 'Dame' : 'Fille');
        document.getElementById('tkd-prev-weight').textContent  = weightCategory;
        preview.style.display = 'flex';
    } else {
        preview.style.display = 'none';
    }
}

// Re-run when gender changes while on step 2
document.querySelector('[name=gender]').addEventListener('change', function() {
    if (currentStep === 2) tkdUpdateCategories();
});
// Re-run if birth_date is corrected while on step 2
document.querySelector('[name=birth_date]').addEventListener('change', function() {
    if (currentStep === 2) tkdUpdateCategories();
});

// ── Event registration guard ──────────────────────────────────────────────────
function checkEventRegistration() {
    var sel     = document.getElementById('event-select');
    var notice  = document.getElementById('event-status-notice');
    var submit  = document.getElementById('submit-btn');
    if (!sel || !notice || !submit) return;

    var opt      = sel.options[sel.selectedIndex];
    var deadline = opt ? opt.getAttribute('data-deadline') : '';

    // Reset state
    notice.style.display = 'none';
    notice.innerHTML = '';
    submit.disabled = false;
    submit.style.opacity = '1';
    submit.style.cursor = 'pointer';
    submit.style.background = '#f59e0b';
    submit.onmouseover = function() { this.style.background = '#fbbf24'; };
    submit.onmouseout  = function() { this.style.background = '#f59e0b'; };

    if (!sel.value) return;

    var now = Date.now();

    if (deadline) {
        var ms      = new Date(deadline).getTime() - now;
        var hours   = Math.floor(ms / 3600000);

        if (ms <= 0) {
            // Deadline has passed — hard lock
            notice.innerHTML =
                '<div style="display:flex;align-items:flex-start;gap:10px;background:rgba(239,68,68,0.07);border:1px solid rgba(239,68,68,0.25);border-radius:8px;padding:14px 16px;">' +
                '<svg style="width:16px;height:16px;color:#f87171;flex-shrink:0;margin-top:1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>' +
                '<div><p style="color:#f87171;font-weight:700;font-size:0.82rem;margin:0 0 2px;">Inscriptions fermées</p>' +
                '<p style="color:rgba(248,113,113,0.7);font-size:0.78rem;margin:0;">La période d\'inscription pour cet événement est terminée. Contactez l\'équipe technique.</p></div></div>';
            notice.style.display = 'block';
            submit.disabled = true;
            submit.style.opacity = '0.4';
            submit.style.cursor = 'not-allowed';
            submit.style.background = '#525252';
            submit.onmouseover = null;
            submit.onmouseout  = null;

        } else if (hours < 48) {
            // Deadline within 48h — amber warning
            var d    = new Date(deadline);
            var fmt  = d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' }) +
                       ' à ' + d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
            notice.innerHTML =
                '<div style="display:flex;align-items:flex-start;gap:10px;background:rgba(245,158,11,0.07);border:1px solid rgba(245,158,11,0.25);border-radius:8px;padding:14px 16px;">' +
                '<svg style="width:16px;height:16px;color:#f59e0b;flex-shrink:0;margin-top:1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' +
                '<p style="color:rgba(245,158,11,0.9);font-size:0.8rem;margin:0;line-height:1.5;">' +
                '<strong>Clôture imminente —</strong> Les inscriptions ferment le ' + fmt + '.</p></div>';
            notice.style.display = 'block';
        }
    }
}

// Run check when arriving on step 3
var _origGoStep = goStep;
goStep = function(n) {
    _origGoStep(n);
    if (n === 3) checkEventRegistration();
};
</script>

</x-public-layout>
