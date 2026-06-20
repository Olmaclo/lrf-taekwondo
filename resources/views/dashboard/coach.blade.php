<x-app-layout title="Espace Coach">

<div x-data="coachDashboard()" x-init="init()" class="space-y-6">

    {{-- ── Welcome banner ──────────────────────────────────────────────────── --}}
    <div class="card-gold p-5 flex items-center gap-5">
        <img src="{{ auth()->user()->avatar_url }}" class="w-14 h-14 rounded-full ring-2 ring-brand-500/50">
        <div class="flex-1 min-w-0">
            <h2 class="text-lg font-bold text-surface-50">Bonjour, {{ auth()->user()->name }}</h2>
            <p class="text-sm text-surface-400">
                Club : <span class="text-surface-200 font-medium">{{ auth()->user()->club ?? 'Non renseigné' }}</span>
                &nbsp;·&nbsp;
                <span x-text="athletes.length + ' athlète(s) enregistré(s)'"></span>
            </p>
        </div>
        <div>
            @if(auth()->user()->account_status === 'approved')
                <span class="badge badge-green">Compte validé</span>
            @elseif(auth()->user()->account_status === 'pending')
                <span class="badge badge-gold">En attente de validation</span>
            @else
                <span class="badge badge-red">Compte rejeté</span>
            @endif
        </div>
    </div>

    {{-- ── Stats ──────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="stat-icon bg-brand-500/15 text-brand-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="stat-value" x-text="athletes.length">0</p>
                <p class="stat-label">Mes athlètes</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-emerald-500/15 text-emerald-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="stat-value" x-text="athletes.filter(a => a.registration_status === 'validated').length">0</p>
                <p class="stat-label">Validés</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-amber-500/15 text-amber-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="stat-value" x-text="athletes.filter(a => a.registration_status === 'pending').length">0</p>
                <p class="stat-label">En attente</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-red-500/15 text-red-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="stat-value" x-text="athletes.filter(a => a.registration_status === 'rejected').length">0</p>
                <p class="stat-label">Rejetés</p>
            </div>
        </div>
    </div>

    {{-- ── Événements ouverts ──────────────────────────────────────────────── --}}
    @if($events->isNotEmpty())
    <div class="card">
        <div class="px-5 py-4 border-b border-surface-700">
            <h2 class="section-title">
                <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Compétitions ouvertes aux inscriptions
            </h2>
        </div>
        <div class="divide-y divide-surface-700/50">
            @foreach($events as $ev)
            <div class="px-5 py-4 flex items-center justify-between gap-4">
                <div>
                    <p class="font-medium text-surface-100">{{ $ev->name }}</p>
                    <p class="text-xs text-surface-500 mt-0.5">
                        {{ $ev->location }} · Du {{ $ev->start_date->format('d/m/Y') }} au {{ $ev->end_date?->format('d/m/Y') ?? '—' }}
                        @if($ev->registration_deadline)
                            · <span class="text-amber-400">Clôture : {{ $ev->registration_deadline->format('d/m/Y H:i') }}</span>
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    @if($ev->registration_fee)
                        <span class="text-xs text-surface-400">{{ number_format($ev->registration_fee, 0, ',', ' ') }} FCFA</span>
                    @endif
                    <a href="{{ route('public.inscription', ['event_id' => $ev->id]) }}" class="btn btn-primary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Inscrire
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Athletes list ────────────────────────────────────────────────── --}}
    <div class="card">
        <div class="px-5 py-4 border-b border-surface-700 flex flex-wrap items-center justify-between gap-3">
            <h2 class="section-title">
                <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Mes athlètes
            </h2>

            {{-- Filters --}}
            <div class="flex items-center gap-2 flex-wrap">
                <select x-model="filterEvent" class="form-select py-1.5 text-xs">
                    <option value="">Tous les événements</option>
                    <template x-for="ev in allEvents" :key="ev.id">
                        <option :value="ev.id" x-text="ev.name"></option>
                    </template>
                </select>
                <select x-model="filterStatus" class="form-select py-1.5 text-xs">
                    <option value="">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="validated">Validés</option>
                    <option value="rejected">Rejetés</option>
                </select>
                <button @click="openAddModal()" class="btn btn-primary btn-sm">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Inscrire
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Athlète</th>
                        <th>Catégorie</th>
                        <th>Événement</th>
                        <th>Inscription</th>
                        <th>Paiement</th>
                        <th class="w-24">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="loading">
                        <tr><td colspan="6" class="text-center py-8 text-surface-500">
                            <div class="spinner mx-auto mb-2"></div> Chargement…
                        </td></tr>
                    </template>
                    <template x-if="!loading && filteredAthletes.length === 0">
                        <tr><td colspan="6" class="text-center py-12">
                            <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
                                <div style="width:48px;height:48px;background:rgba(245,158,11,0.07);border:1px solid rgba(245,158,11,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                    <svg style="width:22px;height:22px;color:#525252;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <p class="text-surface-400 font-medium">Aucun athlète trouvé</p>
                                <button @click="openAddModal()" class="btn btn-primary btn-sm mt-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Inscrire un athlète
                                </button>
                            </div>
                        </td></tr>
                    </template>
                    <template x-for="athlete in filteredAthletes" :key="athlete.id">
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img :src="athlete.photo_url" class="w-8 h-8 rounded-full object-cover ring-1 ring-surface-700">
                                    <div>
                                        <p class="font-medium text-surface-100" x-text="athlete.full_name"></p>
                                        <p class="text-xs text-surface-500" x-text="athlete.age + ' ans · ' + (athlete.gender === 'M' ? 'Masculin' : 'Féminin')"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-xs" x-text="(athlete.age_category ?? '—') + ' · ' + (athlete.weight_category ?? '—')"></td>
                            <td class="text-xs" x-text="athlete.event?.name ?? '—'"></td>
                            <td>
                                <div>
                                    <span :class="{
                                        'badge-green':   athlete.registration_status === 'validated',
                                        'badge-gold':    athlete.registration_status === 'pending',
                                        'badge-red':     athlete.registration_status === 'rejected',
                                    }" class="badge" x-text="athlete.registration_status_label"></span>
                                    {{-- Rejection reason --}}
                                    <template x-if="athlete.registration_status === 'rejected' && athlete.rejection_reason">
                                        <p class="text-xs text-red-400 mt-1 italic" x-text="'Motif : ' + athlete.rejection_reason"></p>
                                    </template>
                                </div>
                            </td>
                            <td>
                                <span :class="{
                                    'badge-green':   athlete.payment_status === 'validated',
                                    'badge-blue':    athlete.payment_status === 'paid',
                                    'badge-orange':  athlete.payment_status === 'temp_validated',
                                    'badge-surface': athlete.payment_status === 'unpaid' || !athlete.payment_status,
                                }" class="badge" x-text="athlete.payment_status_label ?? 'Non payé'"></span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    {{-- Edit (only if not validated) --}}
                                    <template x-if="athlete.registration_status !== 'validated'">
                                        <button @click="openEditModal(athlete)"
                                                class="btn btn-ghost btn-icon p-1.5 text-surface-400 hover:text-brand-400" title="Modifier">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    </template>
                                    {{-- Unregister (only if not validated) --}}
                                    <template x-if="athlete.registration_status !== 'validated'">
                                        <button @click="unregisterAthlete(athlete.id, athlete.full_name)"
                                                class="btn btn-ghost btn-icon p-1.5 text-red-400" title="Désinscrire">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6h12a6 6 0 00-6-6z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-6 6m0-6l6 6"/></svg>
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Add / Edit Athlete Modal ─────────────────────────────────────── --}}
    <div x-show="modal.open"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         class="modal-backdrop" @keydown.escape.window="modal.open = false" style="display:none">
        <div @click.stop class="modal max-w-2xl"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="modal-header">
                <h3 class="font-bold text-surface-50" x-text="modal.editing ? 'Modifier l\'athlète' : 'Inscrire un athlète'"></h3>
                <button @click="modal.open = false" class="btn btn-ghost btn-icon p-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Prénom *</label>
                        <input type="text" x-model="form.first_name" required class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" x-model="form.last_name" required class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de naissance *</label>
                        <input type="date" x-model="form.birth_date" required class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Genre *</label>
                        <select x-model="form.gender" class="form-select">
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Poids (kg)</label>
                        <input type="number" step="0.1" x-model="form.weight" class="form-input" placeholder="ex: 68.5">
                    </div>
                    <div class="form-group">
                        <label class="form-label">N° Licence</label>
                        <input type="text" x-model="form.license_number" class="form-input" placeholder="ex: SEN-2024-001">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Club *</label>
                        <input type="text" x-model="form.club" required class="form-input">
                    </div>
                    <template x-if="!modal.editing">
                        <div class="form-group">
                            <label class="form-label">Compétition *</label>
                            <select x-model="form.event_id" class="form-select" required>
                                <option value="">Sélectionner…</option>
                                <template x-for="ev in events" :key="ev.id">
                                    <option :value="ev.id" x-text="ev.name"></option>
                                </template>
                            </select>
                        </div>
                    </template>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="modal.open = false" class="btn btn-secondary">Annuler</button>
                <button @click="saveAthlete()" :disabled="modal.saving" class="btn btn-primary">
                    <div x-show="modal.saving" class="spinner w-4 h-4"></div>
                    <span x-text="modal.saving ? 'Enregistrement…' : (modal.editing ? 'Enregistrer' : 'Inscrire')"></span>
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function coachDashboard() {
    return {
        athletes: [],
        events: [],
        allEvents: [],
        loading: false,
        filterEvent: '',
        filterStatus: '',
        modal: { open: false, saving: false, editing: false, editId: null },
        form: {},

        get filteredAthletes() {
            return this.athletes.filter(a => {
                if (this.filterEvent && String(a.event?.id) !== String(this.filterEvent)) return false;
                if (this.filterStatus && a.registration_status !== this.filterStatus) return false;
                return true;
            });
        },

        async init() {
            await Promise.all([this.loadAthletes(), this.loadEvents()]);
        },

        async loadAthletes() {
            this.loading = true;
            const data = await api.get('/api/athletes', { per_page: 200 });
            this.athletes = data.data ?? [];
            this.allEvents = [...new Map(
                this.athletes.filter(a => a.event).map(a => [a.event.id, a.event])
            ).values()];
            this.loading = false;
        },

        async loadEvents() {
            const data = await api.get('/api/events');
            this.events = (data.data ?? []).filter(e => e.status === 'open');
        },

        openAddModal(eventId = '') {
            this.form = {
                first_name: '', last_name: '', birth_date: '', gender: 'M',
                weight: '', club: '{{ addslashes(auth()->user()->club ?? '') }}',
                event_id: eventId ? String(eventId) : '', license_number: ''
            };
            this.modal = { open: true, saving: false, editing: false, editId: null };
        },

        openEditModal(athlete) {
            this.form = {
                first_name:     athlete.first_name,
                last_name:      athlete.last_name,
                birth_date:     athlete.birth_date,
                gender:         athlete.gender,
                weight:         athlete.weight ?? '',
                club:           athlete.club,
                license_number: athlete.license_number ?? '',
            };
            this.modal = { open: true, saving: false, editing: true, editId: athlete.id };
        },

        async saveAthlete() {
            if (!this.form.first_name?.trim()) { $store.toast.error('Le prénom est obligatoire.'); return; }
            if (!this.form.last_name?.trim())  { $store.toast.error('Le nom est obligatoire.'); return; }
            if (!this.form.birth_date)         { $store.toast.error('La date de naissance est obligatoire.'); return; }
            if (!this.form.gender)             { $store.toast.error('Le genre est obligatoire.'); return; }
            if (!this.form.club?.trim())       { $store.toast.error('Le club est obligatoire.'); return; }
            if (!this.modal.editing && !this.form.event_id) { $store.toast.error('L\'événement est obligatoire.'); return; }
            this.modal.saving = true;
            try {
                let res;
                if (this.modal.editing) {
                    res = await api.put(`/api/athletes/${this.modal.editId}`, this.form);
                } else {
                    res = await api.post('/api/athletes', this.form);
                }
                if (res.success) {
                    $store.toast.success(res.message ?? 'Enregistré.');
                    this.modal.open = false;
                    this.loadAthletes();
                } else {
                    $store.toast.error(res.message ?? 'Erreur.');
                }
            } finally {
                this.modal.saving = false;
            }
        },

        async unregisterAthlete(id, name) {
            if (!confirm(`Désinscrire ${name} de la compétition ?`)) return;
            const res = await api.delete(`/api/coaches/athletes/${id}/unregister`);
            if (res.success) { $store.toast.success(res.message); this.loadAthletes(); }
            else $store.toast.error(res.message);
        },
    };
}
</script>
@endpush
</x-app-layout>
