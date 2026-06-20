<x-app-layout title="Tableau de bord financier">
@push('actions')
    <a href="{{ route('exports.athletes-xlsx') }}" class="btn btn-secondary btn-sm gap-1.5">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export
    </a>
@endpush

<div x-data="financialDashboard()" x-init="init()" class="space-y-6">

    {{-- ── Stats ──────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="stat-icon bg-emerald-500/15 text-emerald-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="stat-value" x-text="formatMoney(stats.total_collected)">—</p>
                <p class="stat-label">Total collecté</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-brand-500/15 text-brand-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="stat-value" x-text="stats.validated_payments ?? '—'">—</p>
                <p class="stat-label">Paiements validés</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-amber-500/15 text-amber-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="stat-value" x-text="stats.pending_payments ?? '—'">—</p>
                <p class="stat-label">En attente</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-red-500/15 text-red-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="stat-value" x-text="stats.unpaid_athletes ?? '—'">—</p>
                <p class="stat-label">Non payés</p>
            </div>
        </div>
    </div>

    {{-- ── Payment table ───────────────────────────────────────────────── --}}
    <div class="card">
        <div class="px-5 py-4 border-b border-surface-700 flex items-center justify-between gap-3 flex-wrap">
            <h2 class="section-title">
                <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                Paiements
            </h2>
            <div class="flex gap-2">
                <template x-if="selected.length > 0">
                    <div class="flex gap-2">
                        <button @click="bulkTempValidate()" class="btn btn-secondary btn-sm">
                            Pré-valider (<span x-text="selected.length"></span>)
                        </button>
                        <button @click="bulkDefinitiveValidate()" class="btn btn-success btn-sm">
                            Valider (<span x-text="selected.length"></span>)
                        </button>
                    </div>
                </template>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-5 py-3 border-b border-surface-700/50 flex flex-wrap gap-2">
            <div class="search-wrapper flex-1 min-w-40">
                <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model.debounce.300ms="filters.search" class="search-input text-sm" placeholder="Rechercher…">
            </div>
            <select x-model="filters.event_id" @change="loadAthletes()" class="form-select text-sm w-auto">
                <option value="">Tous les événements</option>
                <template x-for="ev in events" :key="ev.id">
                    <option :value="ev.id" x-text="ev.name"></option>
                </template>
            </select>
            <select x-model="filters.payment_status" @change="loadAthletes()" class="form-select text-sm w-auto">
                <option value="">Tous statuts paiement</option>
                <option value="unpaid">Non payé</option>
                <option value="temp_validated">Pré-validé</option>
                <option value="paid">Payé</option>
                <option value="validated">Validé</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-10">
                            <input type="checkbox" class="custom-checkbox"
                                @change="toggleSelectAll($event.target.checked)"
                                :checked="selected.length === athletes.length && athletes.length > 0">
                        </th>
                        <th>Athlète</th>
                        <th>Club</th>
                        <th>Événement</th>
                        <th>Paiement</th>
                        <th>Montant</th>
                        <th>N° Reçu</th>
                        <th class="w-32">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="loading">
                        <tr><td colspan="8" class="text-center py-8 text-surface-500">
                            <div class="spinner mx-auto mb-2"></div> Chargement…
                        </td></tr>
                    </template>
                    <template x-if="!loading && filtered.length === 0">
                        <tr><td colspan="8" class="text-center py-12 text-surface-500">Aucun résultat</td></tr>
                    </template>
                    <template x-for="athlete in filtered" :key="athlete.id">
                        <tr>
                            <td>
                                <input type="checkbox" class="custom-checkbox" :value="athlete.id" x-model="selected">
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img :src="athlete.photo_url" class="w-8 h-8 rounded-full object-cover ring-1 ring-surface-700">
                                    <div>
                                        <p class="font-medium text-surface-100" x-text="athlete.full_name"></p>
                                        <p class="text-xs text-surface-500" x-text="athlete.coach_name ?? ''"></p>
                                    </div>
                                </div>
                            </td>
                            <td x-text="athlete.club ?? '—'"></td>
                            <td class="text-xs" x-text="athlete.event?.name ?? '—'"></td>
                            <td>
                                <span :class="{
                                    'badge-green':   athlete.payment_status === 'validated',
                                    'badge-blue':    athlete.payment_status === 'paid',
                                    'badge-orange':  athlete.payment_status === 'temp_validated',
                                    'badge-surface': athlete.payment_status === 'unpaid' || !athlete.payment_status,
                                }" class="badge" x-text="athlete.payment_status_label ?? 'Non payé'"></span>
                            </td>
                            <td x-text="athlete.payment_amount ? formatMoney(athlete.payment_amount) : '—'"></td>
                            <td>
                                <span x-text="athlete.receipt_number ?? '—'" class="font-mono text-xs text-surface-400"></span>
                            </td>
                            <td>
                                <div class="flex gap-1 flex-wrap">
                                    <template x-if="!athlete.payment_status || athlete.payment_status === 'unpaid'">
                                        <button @click="openPaymentModal(athlete)" class="btn btn-primary btn-sm py-1 px-2 text-xs">
                                            Encaisser
                                        </button>
                                    </template>
                                    <template x-if="athlete.payment_status === 'temp_validated'">
                                        <button @click="definitiveValidate(athlete.id)" class="btn btn-success btn-sm py-1 px-2 text-xs">
                                            Valider
                                        </button>
                                    </template>
                                    <template x-if="athlete.payment_status === 'paid' || athlete.payment_status === 'validated'">
                                        <a :href="`/api/financial/athletes/${athlete.id}/receipt`" target="_blank"
                                           class="btn btn-ghost btn-icon p-1.5" title="Télécharger reçu">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Bulk Temp-Validate Modal ─────────────────────────────────────── --}}
    <div x-show="bulkTempModal.open"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         class="modal-backdrop" @keydown.escape.window="bulkTempModal.open = false" style="display:none">
        <div @click.stop class="modal"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="modal-header">
                <h3 class="font-bold text-surface-50">Pré-valider les paiements</h3>
                <button @click="bulkTempModal.open = false" class="btn btn-ghost btn-icon p-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="modal-body space-y-4">
                <p class="text-sm text-surface-300">
                    <span class="font-semibold text-surface-100" x-text="selected.length"></span> athlète(s) sélectionné(s) seront passés en statut <span class="text-amber-400 font-medium">Pré-validé</span>.
                </p>
                <div class="form-group">
                    <label class="form-label">Date limite de validation définitive <span class="text-surface-500">(optionnel)</span></label>
                    <input type="date" x-model="bulkTempModal.deadline" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes <span class="text-surface-500">(optionnel)</span></label>
                    <textarea x-model="bulkTempModal.notes" rows="2" class="form-input resize-none" placeholder="Ex : en attente de confirmation de virement…"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="bulkTempModal.open = false" class="btn btn-secondary">Annuler</button>
                <button @click="confirmBulkTempValidate()" :disabled="bulkTempModal.saving" class="btn btn-warning">
                    <div x-show="bulkTempModal.saving" class="spinner w-4 h-4"></div>
                    <span x-text="bulkTempModal.saving ? 'Traitement…' : 'Confirmer la pré-validation'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Payment Modal ────────────────────────────────────────────────── --}}
    <div x-show="payModal.open"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         class="modal-backdrop" @keydown.escape.window="payModal.open = false" style="display:none">
        <div @click.stop class="modal"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="modal-header">
                <h3 class="font-bold text-surface-50">Enregistrer un paiement</h3>
                <button @click="payModal.open = false" class="btn btn-ghost btn-icon p-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="modal-body space-y-4">
                <p class="text-sm text-surface-300">
                    Athlète : <span class="font-semibold text-surface-100" x-text="payModal.athleteName"></span>
                </p>
                <div class="form-group">
                    <label class="form-label">Montant (FCFA)</label>
                    <input type="number" step="100" x-model="payForm.amount" required class="form-input" placeholder="5000">
                </div>
                <div class="form-group">
                    <label class="form-label">Mode de paiement</label>
                    <select x-model="payForm.payment_method" class="form-select">
                        <option value="cash">Espèces</option>
                        <option value="transfer">Virement</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="check">Chèque</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">N° de transaction (optionnel)</label>
                    <input type="text" x-model="payForm.transaction_ref" class="form-input" placeholder="REF-XXXX">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea x-model="payForm.notes" rows="2" class="form-input resize-none" placeholder="Notes optionnelles…"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="payModal.open = false" class="btn btn-secondary">Annuler</button>
                <button @click="savePayment()" :disabled="payModal.saving" class="btn btn-primary">
                    <div x-show="payModal.saving" class="spinner w-4 h-4"></div>
                    <span x-text="payModal.saving ? 'Enregistrement…' : 'Enregistrer'"></span>
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function financialDashboard() {
    return {
        athletes: [],
        events: [],
        selected: [],
        loading: false,
        stats: {},
        filters: { search: '', event_id: '', payment_status: '' },
        payModal: { open: false, saving: false, athleteId: null, athleteName: '' },
        payForm: { amount: '', payment_method: 'cash', transaction_ref: '', notes: '' },
        bulkTempModal: { open: false, saving: false, deadline: '', notes: '' },

        get filtered() {
            return this.athletes;
        },

        async init() {
            await Promise.all([this.loadAthletes(), this.loadEvents(), this.loadStats()]);
            this.$watch('filters.search', () => this.loadAthletes());
        },

        async loadStats() {
            const data = await api.get('/dashboard');
            this.stats = data.data ?? {};
        },

        async loadAthletes() {
            this.loading = true;
            const params = {
                event_id:       this.filters.event_id,
                payment_status: this.filters.payment_status,
                search:         this.filters.search,
            };
            const data = await api.get('/api/athletes', params);
            this.athletes = data.data ?? [];
            this.selected = [];
            this.loading = false;
        },

        async loadEvents() {
            const data = await api.get('/api/events');
            this.events = data.data ?? [];
        },

        toggleSelectAll(checked) {
            this.selected = checked ? this.filtered.map(a => a.id) : [];
        },

        formatMoney(v) {
            if (!v && v !== 0) return '—';
            return new Intl.NumberFormat('fr-FR').format(v) + ' FCFA';
        },

        openPaymentModal(athlete) {
            this.payModal = { open: true, saving: false, athleteId: athlete.id, athleteName: athlete.full_name };
            this.payForm = { amount: athlete.event?.registration_fee ?? '', payment_method: 'cash', transaction_ref: '', notes: '' };
        },

        async savePayment() {
            this.payModal.saving = true;
            try {
                const res = await api.post(`/api/financial/athletes/${this.payModal.athleteId}/payment`, this.payForm);
                if (res.success) {
                    $store.toast.success(res.message ?? 'Paiement enregistré.');
                    this.payModal.open = false;
                    await Promise.all([this.loadAthletes(), this.loadStats()]);
                } else {
                    $store.toast.error(res.message ?? 'Erreur.');
                }
            } finally {
                this.payModal.saving = false;
            }
        },

        async definitiveValidate(id) {
            const res = await api.post(`/api/financial/athletes/${id}/validate`);
            if (res.success) { $store.toast.success(res.message); await Promise.all([this.loadAthletes(), this.loadStats()]); }
            else $store.toast.error(res.message);
        },

        bulkTempValidate() {
            this.bulkTempModal = { open: true, saving: false, deadline: '', notes: '' };
        },

        async confirmBulkTempValidate() {
            this.bulkTempModal.saving = true;
            try {
                const res = await api.post('/api/financial/bulk-temp-validate', {
                    ids:      this.selected,
                    deadline: this.bulkTempModal.deadline || null,
                    notes:    this.bulkTempModal.notes || null,
                });
                if (res.success) {
                    $store.toast.success(res.message);
                    this.bulkTempModal.open = false;
                    this.selected = [];
                    await Promise.all([this.loadAthletes(), this.loadStats()]);
                } else {
                    $store.toast.error(res.message ?? 'Erreur.');
                }
            } finally {
                this.bulkTempModal.saving = false;
            }
        },

        async bulkDefinitiveValidate() {
            const res = await api.post('/api/financial/bulk-validate', { ids: this.selected });
            if (res.success) { $store.toast.success(res.message); this.selected = []; await Promise.all([this.loadAthletes(), this.loadStats()]); }
        },
    };
}
</script>
@endpush
</x-app-layout>
