<x-app-layout title="Tableau de bord technique">
@push('actions')
    <div class="flex gap-2">
        <a href="{{ route('exports.athletes-xlsx') }}" class="btn btn-secondary btn-sm gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            XLSX
        </a>
        <a href="{{ route('exports.athletes-csv') }}" class="btn btn-secondary btn-sm gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            CSV
        </a>
    </div>
@endpush

<div x-data="technicalDashboard()" x-init="init()" class="space-y-5">

    {{-- ── Stats cards ──────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="stat-icon bg-brand-500/15 text-brand-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div><p class="stat-value" x-text="stats.total_athletes ?? '—'">—</p><p class="stat-label">Athlètes</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-emerald-500/15 text-emerald-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div><p class="stat-value" x-text="stats.validated_athletes ?? '—'">—</p><p class="stat-label">Validés</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-amber-500/15 text-amber-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div><p class="stat-value" x-text="stats.pending_athletes ?? '—'">—</p><p class="stat-label">En attente</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-blue-500/15 text-blue-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div><p class="stat-value" x-text="stats.total_events ?? '—'">—</p><p class="stat-label">Événements</p></div>
        </div>
    </div>

    {{-- ── Tab navigation ────────────────────────────────────────────────────── --}}
    <div class="card p-0 overflow-hidden">
        <div class="flex overflow-x-auto border-b border-surface-700 scrollbar-none">
            <template x-for="t in tabs" :key="t.id">
                <button
                    @click="switchTab(t.id)"
                    :class="tab === t.id ? 'border-brand-400 text-brand-400 bg-surface-700/40' : 'border-transparent text-surface-400 hover:text-surface-200'"
                    class="flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition-colors flex-shrink-0">
                    <span x-text="t.icon"></span>
                    <span x-text="t.label"></span>
                    <template x-if="t.badge !== null && t.badge !== undefined">
                        <span class="badge badge-gold text-xs px-1.5 py-0.5 min-w-5 text-center" x-text="t.badge"></span>
                    </template>
                </button>
            </template>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             TAB: ÉVÉNEMENTS
        ══════════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'events'" class="p-5 space-y-4">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="search-wrapper w-64">
                    <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model.debounce.300ms="eventSearch" class="search-input text-sm" placeholder="Rechercher un événement…">
                </div>
                <button @click="openEventModal()" class="btn btn-primary btn-sm gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nouvel événement
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead><tr>
                        <th>Événement</th><th>Type</th><th>Dates</th><th>Lieu</th><th>Statut</th><th>Athlètes</th><th class="w-24">Actions</th>
                    </tr></thead>
                    <tbody>
                        <template x-if="eventsLoading">
                            <tr><td colspan="7" class="text-center py-8 text-surface-500"><div class="spinner mx-auto mb-2"></div>Chargement…</td></tr>
                        </template>
                        <template x-if="!eventsLoading && filteredEvents.length === 0">
                            <tr><td colspan="7" class="text-center py-10 text-surface-500">Aucun événement trouvé</td></tr>
                        </template>
                        <template x-for="ev in filteredEvents" :key="ev.id">
                            <tr>
                                <td><p class="font-medium text-surface-100" x-text="ev.name"></p></td>
                                <td><span class="badge badge-blue text-xs" x-text="ev.type_label"></span></td>
                                <td class="text-xs text-surface-400" x-text="ev.start_date + (ev.end_date ? ' → ' + ev.end_date : '')"></td>
                                <td class="text-sm text-surface-400" x-text="ev.location ?? '—'"></td>
                                <td><span class="badge text-xs" :class="ev.status_color" x-text="ev.status_label"></span></td>
                                <td class="text-sm" x-text="ev.athletes_count"></td>
                                <td>
                                    <div class="flex gap-1">
                                        <button @click="openEditEventModal(ev)" class="btn btn-ghost btn-icon p-1.5" title="Modifier">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button @click="deleteEvent(ev.id, ev.name)" class="btn btn-ghost btn-icon p-1.5 text-red-400" title="Supprimer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             TAB: ATHLÈTES
        ══════════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'athletes'" class="p-5 space-y-3">

            {{-- Row 1: main search + event + status + add button --}}
            <div class="flex flex-wrap items-center gap-2 justify-between">
                <div class="flex flex-wrap gap-2 flex-1">
                    <div class="search-wrapper w-48">
                        <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" x-model.debounce.300ms="filters.search" class="search-input text-sm" placeholder="Nom, prénom, licence…">
                    </div>
                    <select x-model="filters.event_id" @change="page=1;loadAthletes()" class="form-select text-sm w-auto">
                        <option value="">Tous les événements</option>
                        <template x-for="ev in events" :key="ev.id">
                            <option :value="ev.id" x-text="ev.name"></option>
                        </template>
                    </select>
                    <select x-model="filters.registration_status" @change="page=1;loadAthletes()" class="form-select text-sm w-auto">
                        <option value="">Tous statuts</option>
                        <option value="pending">En attente</option>
                        <option value="validated">Validé</option>
                        <option value="rejected">Rejeté</option>
                    </select>
                </div>
                <button @click="openAddModal()" class="btn btn-primary btn-sm gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajouter
                </button>
            </div>

            {{-- Row 2: category + club filters --}}
            <div class="flex flex-wrap gap-2 items-center">
                <select x-model="filters.age_category" @change="filters.weight_category=''; page=1; loadAthletes()" class="form-select text-sm w-auto">
                    <option value="">Toutes catégories d'âge</option>
                    <option>Benjamin</option><option>Minime</option><option>Cadet</option><option>Junior</option><option>Senior</option>
                </select>
                <select x-model="filters.gender" @change="page=1;loadAthletes()" class="form-select text-sm w-auto">
                    <option value="">Tous genres</option>
                    <option value="M">♂ Masculin</option>
                    <option value="F">♀ Féminin</option>
                </select>
                <select x-model="filters.weight_category" @change="page=1;loadAthletes()" class="form-select text-sm w-auto" :disabled="!filters.age_category">
                    <option value="">Toutes catégories de poids</option>
                    <template x-for="w in weightCategoryOptions" :key="w">
                        <option :value="w" x-text="w"></option>
                    </template>
                </select>
                <div class="search-wrapper w-40">
                    <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <input type="text" x-model.debounce.400ms="filters.club" @input.debounce.400ms="page=1;loadAthletes()" class="search-input text-sm" placeholder="Filtrer par club…">
                </div>
                <template x-if="filters.age_category || filters.gender || filters.weight_category || filters.club || filters.registration_status">
                    <button @click="filters.age_category=''; filters.gender=''; filters.weight_category=''; filters.club=''; filters.registration_status=''; page=1; loadAthletes()"
                            class="btn btn-ghost btn-sm text-surface-400 hover:text-surface-200 gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Réinitialiser
                    </button>
                </template>
            </div>

            {{-- Row 3: bulk selection actions --}}
            <template x-if="selected.length > 0">
                <div class="flex items-center gap-2 p-2.5 bg-brand-500/08 border border-brand-500/20 rounded-lg">
                    <span class="text-xs font-semibold text-brand-400" x-text="selected.length + ' athlète(s) sélectionné(s)'"></span>
                    <div class="flex gap-2 ml-auto">
                        <button @click="bulkValidate()" class="btn btn-success btn-sm">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Valider (<span x-text="selected.length"></span>)
                        </button>
                        <button @click="bulkDelete()" class="btn btn-danger btn-sm">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Supprimer
                        </button>
                        <button @click="selected = []" class="btn btn-ghost btn-sm text-surface-400">Désélectionner</button>
                    </div>
                </div>
            </template>

            {{-- Row 4: club batch actions (shown when club filter is active) --}}
            <template x-if="filters.club && selected.length === 0">
                <div class="flex items-center gap-3 p-2.5 bg-surface-700/40 border border-surface-600/50 rounded-lg">
                    <svg class="w-4 h-4 text-brand-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="text-xs text-surface-300">Actions pour le club <strong class="text-surface-100" x-text="filters.club"></strong></span>
                    <div class="flex gap-2 ml-auto">
                        <button @click="validateByClub()" class="btn btn-success btn-sm">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Valider tout le club
                        </button>
                        <button @click="deleteByClub()" class="btn btn-danger btn-sm">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Supprimer tout le club
                        </button>
                    </div>
                </div>
            </template>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead><tr>
                        <th class="w-10"><input type="checkbox" class="custom-checkbox" @change="toggleSelectAll($event.target.checked)" :checked="selected.length === athletes.length && athletes.length > 0"></th>
                        <th>Athlète</th><th>Catégorie</th><th>Club</th><th>Poids</th><th>Statut</th><th class="w-24">Actions</th>
                    </tr></thead>
                    <tbody>
                        <template x-if="loading"><tr><td colspan="7" class="text-center py-8 text-surface-500"><div class="spinner mx-auto mb-2"></div>Chargement…</td></tr></template>
                        <template x-if="!loading && athletes.length === 0"><tr><td colspan="7" class="text-center py-10 text-surface-500">Aucun athlète trouvé</td></tr></template>
                        <template x-for="athlete in athletes" :key="athlete.id">
                            <tr>
                                <td><input type="checkbox" class="custom-checkbox" :value="athlete.id" x-model="selected"></td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img :src="athlete.photo_url" class="w-8 h-8 rounded-full object-cover ring-1 ring-surface-700">
                                        <div>
                                            <p class="font-medium text-surface-100" x-text="athlete.full_name"></p>
                                            <p class="text-xs text-surface-500" x-text="athlete.coach_name ?? '—'"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-xs" x-text="(athlete.age_category ?? '') + ' · ' + (athlete.gender === 'M' ? '♂' : '♀') + ' · ' + (athlete.weight_category ?? '')"></td>
                                <td x-text="athlete.club ?? '—'"></td>
                                <td x-text="athlete.weight ? athlete.weight + ' kg' : '—'"></td>
                                <td><span class="badge text-xs" :class="{'badge-green': athlete.registration_status==='validated','badge-gold': athlete.registration_status==='pending','badge-red': athlete.registration_status==='rejected','badge-surface': !athlete.registration_status}" x-text="athlete.registration_status_label"></span></td>
                                <td>
                                    <div class="flex gap-1">
                                        <button @click="openEditModal(athlete)" class="btn btn-ghost btn-icon p-1.5" title="Modifier">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <template x-if="athlete.registration_status === 'pending'">
                                            <button @click="validateAthlete(athlete.id)" class="btn btn-ghost btn-icon p-1.5 text-emerald-400" title="Valider">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </template>
                                        <button @click="deleteAthlete(athlete.id, athlete.full_name)" class="btn btn-ghost btn-icon p-1.5 text-red-400" title="Supprimer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between text-sm text-surface-500 pt-1">
                <span>
                    <span x-text="athletesMeta.total ?? athletes.length" class="font-semibold text-surface-300"></span>
                    <span> athlète(s) au total</span>
                    <template x-if="selected.length > 0">
                        <span class="ml-2 text-brand-400" x-text="' · ' + selected.length + ' sélectionné(s)'"></span>
                    </template>
                </span>
                <div class="flex items-center gap-1">
                    <button @click="page > 1 && (page--, loadAthletes())" :disabled="page<=1" class="btn btn-ghost btn-sm btn-icon" :class="page<=1?'opacity-30':''">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <span class="px-2 py-1 text-surface-400 text-xs" x-text="`Page ${page} / ${athletesMeta.last_page ?? 1}`"></span>
                    <button @click="page < (athletesMeta.last_page ?? 1) && (page++, loadAthletes())" :disabled="page >= (athletesMeta.last_page ?? 1)" class="btn btn-ghost btn-sm btn-icon" :class="page >= (athletesMeta.last_page ?? 1) ? 'opacity-30' : ''">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             TAB: COACHS
        ══════════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'coaches'" class="p-5 space-y-4">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="search-wrapper w-64">
                    <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model.debounce.300ms="coachSearch" class="search-input text-sm" placeholder="Rechercher un coach…">
                </div>
                <div class="flex gap-2">
                    <template x-if="selectedCoaches.length > 0">
                        <div class="flex gap-2">
                            <button @click="bulkValidateCoaches()" class="btn btn-success btn-sm">Valider (<span x-text="selectedCoaches.length"></span>)</button>
                            <button @click="bulkRejectCoaches()" class="btn btn-danger btn-sm">Rejeter</button>
                        </div>
                    </template>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead><tr>
                        <th class="w-10"><input type="checkbox" class="custom-checkbox" @change="toggleSelectAllCoaches($event.target.checked)"></th>
                        <th>Coach</th><th>Club</th><th>Athlètes</th><th>Statut</th><th class="w-32">Actions</th>
                    </tr></thead>
                    <tbody>
                        <template x-if="coachesLoading"><tr><td colspan="6" class="text-center py-8 text-surface-500"><div class="spinner mx-auto mb-2"></div>Chargement…</td></tr></template>
                        <template x-if="!coachesLoading && filteredCoaches.length === 0"><tr><td colspan="6" class="text-center py-10 text-surface-500">Aucun coach trouvé</td></tr></template>
                        <template x-for="coach in filteredCoaches" :key="coach.id">
                            <tr>
                                <td><input type="checkbox" class="custom-checkbox" :value="coach.id" x-model="selectedCoaches"></td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img :src="coach.avatar_url" class="w-8 h-8 rounded-full object-cover ring-1 ring-surface-700">
                                        <div>
                                            <p class="font-medium text-surface-100" x-text="coach.name"></p>
                                            <p class="text-xs text-surface-500" x-text="coach.email"></p>
                                        </div>
                                    </div>
                                </td>
                                <td x-text="coach.club ?? '—'"></td>
                                <td><span class="badge badge-blue text-xs" x-text="coach.athletes_count + ' athlète(s)'"></span></td>
                                <td>
                                    <span class="badge text-xs" :class="coach.is_validated ? 'badge-green' : (coach.account_status === 'rejected' ? 'badge-red' : 'badge-gold')"
                                          x-text="coach.is_validated ? 'Validé' : (coach.account_status === 'rejected' ? 'Rejeté' : 'En attente')"></span>
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        <template x-if="!coach.is_validated && coach.account_status !== 'rejected'">
                                            <button @click="validateCoach(coach.id)" class="btn btn-success btn-sm">Valider</button>
                                        </template>
                                        <template x-if="coach.is_validated">
                                            <button @click="rejectCoach(coach.id)" class="btn btn-secondary btn-sm">Suspendre</button>
                                        </template>
                                        <button @click="deleteCoach(coach.id, coach.name)" class="btn btn-ghost btn-icon p-1.5 text-red-400">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             TAB: UTILISATEURS
        ══════════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'users'" class="p-5 space-y-4">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="flex gap-2 flex-wrap">
                    <div class="search-wrapper w-56">
                        <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" x-model.debounce.300ms="userSearch" class="search-input text-sm" placeholder="Rechercher…">
                    </div>
                    <select x-model="userRoleFilter" @change="loadUsers()" class="form-select text-sm w-auto">
                        <option value="">Tous les rôles</option>
                        <option value="admin">Admin</option>
                        <option value="technical">Technique</option>
                        <option value="financial">Financier</option>
                        <option value="coach">Coach</option>
                    </select>
                </div>
                <button x-show="isAdmin" @click="openUserModal()" class="btn btn-primary btn-sm gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nouvel utilisateur
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead><tr><th>Utilisateur</th><th>Rôle</th><th>Club</th><th>Statut</th><th>Inscrit le</th><th class="w-24">Actions</th></tr></thead>
                    <tbody>
                        <template x-if="usersLoading"><tr><td colspan="6" class="text-center py-8 text-surface-500"><div class="spinner mx-auto mb-2"></div>Chargement…</td></tr></template>
                        <template x-if="!usersLoading && users.length === 0"><tr><td colspan="6" class="text-center py-10 text-surface-500">Aucun utilisateur trouvé</td></tr></template>
                        <template x-for="u in users" :key="u.id">
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img :src="u.avatar_url" class="w-8 h-8 rounded-full object-cover ring-1 ring-surface-700">
                                        <div><p class="font-medium text-surface-100" x-text="u.name"></p><p class="text-xs text-surface-500" x-text="u.email"></p></div>
                                    </div>
                                </td>
                                <td>
                                    <select :value="u.role" @change="changeUserRole(u.id, $event.target.value)" class="form-select text-xs py-1 w-auto">
                                        <option value="admin">Admin</option>
                                        <option value="technical">Technique</option>
                                        <option value="financial">Financier</option>
                                        <option value="coach">Coach</option>
                                    </select>
                                </td>
                                <td x-text="u.club ?? '—'"></td>
                                <td>
                                    <button @click="toggleUserValidation(u.id)" class="badge text-xs cursor-pointer" :class="u.is_validated ? 'badge-green' : 'badge-gold'"
                                            x-text="u.is_validated ? 'Actif' : 'En attente'" title="Cliquer pour changer"></button>
                                </td>
                                <td class="text-xs text-surface-500" x-text="u.created_at"></td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <button @click="sendPasswordReset(u.id, u.name, u.email)" class="btn btn-ghost btn-icon p-1.5 text-amber-400" title="Envoyer un email de réinitialisation de mot de passe">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </button>
                                        <button x-show="isAdmin" @click="deleteUser(u.id, u.name)" class="btn btn-ghost btn-icon p-1.5 text-red-400" title="Supprimer l'utilisateur">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             TAB: CATÉGORIES
        ══════════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'categories'" class="p-5 space-y-4">
            <div class="flex items-center gap-3 flex-wrap">
                <select x-model="catGender" class="form-select text-sm w-auto">
                    <option value="">Tous genres</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
                <select x-model="catAge" class="form-select text-sm w-auto">
                    <option value="">Toutes catégories d'âge</option>
                    <option>Benjamin</option><option>Minime</option><option>Cadet</option><option>Junior</option><option>Senior</option>
                </select>
            </div>
            <div class="grid gap-4">
                <template x-for="group in filteredCategories" :key="group.age + group.gender">
                    <div class="bg-surface-700/30 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="badge badge-blue text-sm font-semibold" x-text="group.age"></span>
                            <span class="text-surface-300 font-medium" x-text="(function(){ var s = group.age.toLowerCase() === 'senior'; return group.gender === 'M' ? (s ? '♂ Homme' : '♂ Garçon') : (s ? '♀ Dame' : '♀ Fille'); })()"></span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="w in group.weights" :key="w">
                                <span class="px-3 py-1 bg-surface-700 rounded-full text-sm text-surface-200 border border-surface-600" x-text="w"></span>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             TAB: TIRAGES
        ══════════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'draws'" class="p-5 space-y-5">
            <div class="grid lg:grid-cols-3 gap-5">
                {{-- Generator --}}
                <div class="card p-4 space-y-3 lg:col-span-1">
                    <h3 class="section-title text-sm">Générer un tirage</h3>
                    <div class="form-group">
                        <label class="form-label">Événement</label>
                        <select x-model="drawForm.event_id" @change="loadDrawCategories()" class="form-select text-sm">
                            <option value="">Sélectionner…</option>
                            <template x-for="ev in events" :key="ev.id"><option :value="ev.id" x-text="ev.name"></option></template>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catégorie</label>
                        <select x-model="drawForm.category_key" class="form-select text-sm" :disabled="!drawForm.event_id">
                            <option value="">Sélectionner…</option>
                            <template x-for="cat in drawCategories" :key="cat.key"><option :value="cat.key" x-text="cat.label + ' (' + cat.count + ')'"></option></template>
                        </select>
                    </div>
                    <button @click="generateDraw()" :disabled="!drawForm.event_id || !drawForm.category_key || drawGenerating" class="btn btn-primary w-full justify-center">
                        <div x-show="drawGenerating" class="spinner w-4 h-4"></div>
                        <span x-text="drawGenerating ? 'Génération…' : 'Générer le tirage'"></span>
                    </button>
                </div>
                {{-- Draws list --}}
                <div class="lg:col-span-2 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="section-title text-sm">Tirages existants</h3>
                        <select x-model="drawsEventFilter" @change="loadDrawsList()" class="form-select text-sm w-auto">
                            <option value="">Tous les événements</option>
                            <template x-for="ev in events" :key="ev.id"><option :value="ev.id" x-text="ev.name"></option></template>
                        </select>
                    </div>
                    <template x-if="drawsLoading"><div class="text-center py-8 text-surface-500"><div class="spinner mx-auto mb-2"></div>Chargement…</div></template>
                    <template x-if="!drawsLoading && draws.length === 0"><div class="text-center py-10 text-surface-500">Aucun tirage généré</div></template>
                    <template x-for="draw in draws" :key="draw.id">
                        <div class="bg-surface-700/30 rounded-lg p-4 flex items-center justify-between gap-3">
                            <div>
                                <p class="font-medium text-surface-100" x-text="draw.category"></p>
                                <p class="text-xs text-surface-500 mt-0.5" x-text="draw.event_name + ' · ' + draw.total_athletes + ' athlètes · ' + (draw.use_pools ? 'Poules' : 'Élimination directe')"></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-surface-500" x-text="draw.generated_at"></span>
                                <button @click="deleteDraw(draw.id)" class="btn btn-ghost btn-icon p-1.5 text-red-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             TAB: EXPORTS
        ══════════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'exports'" class="p-5 space-y-5">

            {{-- Shared filters --}}
            <div class="card p-4">
                <p class="text-xs font-semibold text-surface-400 uppercase tracking-widest mb-3">Filtres (appliqués à tous les exports)</p>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                    <div class="form-group">
                        <label class="form-label">Événement</label>
                        <select x-model="exportEventId" class="form-select text-sm">
                            <option value="">Tous</option>
                            <template x-for="ev in events" :key="ev.id"><option :value="ev.id" x-text="ev.name"></option></template>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catégorie d'âge</label>
                        <select x-model="exportAgeCategory" @change="exportWeightCategory=''" class="form-select text-sm">
                            <option value="">Toutes</option>
                            <option>Benjamin</option><option>Minime</option><option>Cadet</option><option>Junior</option><option>Senior</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Genre</label>
                        <select x-model="exportGender" class="form-select text-sm">
                            <option value="">Tous</option>
                            <option value="M">♂ Masculin</option>
                            <option value="F">♀ Féminin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catégorie de poids</label>
                        <select x-model="exportWeightCategory" class="form-select text-sm" :disabled="!exportAgeCategory">
                            <option value="">Toutes</option>
                            <template x-for="w in exportWeightOptions" :key="w"><option :value="w" x-text="w"></option></template>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Statut inscription</label>
                        <select x-model="exportStatus" class="form-select text-sm">
                            <option value="">Tous</option>
                            <option value="pending">En attente</option>
                            <option value="validated">Validés</option>
                            <option value="rejected">Rejetés</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Club</label>
                        <input type="text" x-model="exportClub" class="form-input text-sm" placeholder="Nom du club…">
                    </div>
                </div>
            </div>

            {{-- Export cards --}}
            <div class="grid md:grid-cols-3 gap-5">

                {{-- XLSX --}}
                <div class="card p-5 space-y-4 flex flex-col">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-500/15 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-surface-100 text-sm">Excel (XLSX)</h3>
                            <p class="text-xs text-surface-500">Toutes colonnes, groupé par catégorie</p>
                        </div>
                    </div>
                    <p class="text-xs text-surface-400 flex-1">Tableau complet : nom, club, catégorie d'âge, catégorie de poids, statut inscription, paiement, coach, licence…</p>
                    <a :href="'/exports/athletes/xlsx' + buildExportQuery()" class="btn btn-primary w-full justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Télécharger XLSX
                    </a>
                </div>

                {{-- CSV --}}
                <div class="card p-5 space-y-4 flex flex-col">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-500/15 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-surface-100 text-sm">CSV</h3>
                            <p class="text-xs text-surface-500">Compatible tableurs et BDD</p>
                        </div>
                    </div>
                    <p class="text-xs text-surface-400 flex-1">Format texte universel pour import dans LibreOffice, Excel, Google Sheets, bases de données. Séparateur point-virgule, encodage UTF-8.</p>
                    <a :href="'/exports/athletes/csv' + buildExportQuery()" class="btn btn-secondary w-full justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Télécharger CSV
                    </a>
                </div>

                {{-- PDF --}}
                <div class="card p-5 space-y-4 flex flex-col border-brand-500/20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand-500/15 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-surface-100 text-sm">PDF officiel</h3>
                            <p class="text-xs text-surface-500">Groupé par catégorie de poids</p>
                        </div>
                    </div>
                    <p class="text-xs text-surface-400 flex-1">Document imprimable groupé par catégorie d'âge → genre → catégorie de poids. Idéal pour les tableaux d'affichage et documents officiels.</p>
                    <a :href="'/exports/athletes/pdf' + buildExportQuery()" class="btn btn-primary w-full justify-center gap-2" style="background:#f59e0b; color:#000;" onmouseover="this.style.background='#fbbf24'" onmouseout="this.style.background='#f59e0b'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Télécharger PDF
                    </a>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             TAB: CLASSEMENT
        ══════════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'ranking'" class="p-5 space-y-4">
            <div class="flex flex-wrap items-center gap-3 justify-between">
                <div class="flex flex-wrap gap-2">
                    <select x-model="rankingSeason" @change="loadRankings()" class="form-select text-sm w-auto">
                        <template x-for="y in rankingSeasons" :key="y"><option :value="y" x-text="y"></option></template>
                    </select>
                    <select x-model="rankingEventId" @change="loadRankings()" class="form-select text-sm w-auto">
                        <option value="">Tous les événements</option>
                        <template x-for="ev in events" :key="ev.id"><option :value="ev.id" x-text="ev.name"></option></template>
                    </select>
                    <select x-model="rankingCategory" @change="loadRankings()" class="form-select text-sm w-auto">
                        <option value="">Toutes catégories</option>
                        <template x-for="cat in rankingCategoryOptions" :key="cat"><option :value="cat" x-text="cat"></option></template>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button @click="openAddRankingModal()" class="btn btn-secondary btn-sm gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajouter
                    </button>
                    <button @click="openRecalculateModal()" class="btn btn-primary btn-sm gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Recalculer depuis tirages
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead><tr><th class="w-10">#</th><th>Athlète</th><th>Club</th><th>Catégorie</th><th>Événement</th><th>Pts</th><th>V</th><th>D</th><th>Médaille</th><th class="w-10">Del</th></tr></thead>
                    <tbody>
                        <template x-if="rankingLoading"><tr><td colspan="10" class="text-center py-8 text-surface-500"><div class="spinner mx-auto mb-2"></div>Chargement…</td></tr></template>
                        <template x-if="!rankingLoading && rankings.length === 0"><tr><td colspan="10" class="text-center py-10 text-surface-500">Aucun classement trouvé</td></tr></template>
                        <template x-for="(r, i) in rankings" :key="r.id">
                            <tr>
                                <td class="font-bold text-brand-400" x-text="r.position ?? i+1"></td>
                                <td class="font-medium text-surface-100" x-text="r.athlete?.full_name ?? '—'"></td>
                                <td class="text-surface-400 text-sm" x-text="r.athlete?.club ?? '—'"></td>
                                <td class="text-xs text-surface-400" x-text="r.category"></td>
                                <td class="text-xs text-surface-400" x-text="r.event?.name ?? '—'"></td>
                                <td class="font-bold text-brand-400" x-text="r.points"></td>
                                <td class="text-emerald-400" x-text="r.wins"></td>
                                <td class="text-red-400" x-text="r.losses"></td>
                                <td><span x-show="r.medal" class="badge text-xs" :class="r.medal_color" x-text="r.medal"></span></td>
                                <td>
                                    <button @click="deleteRanking(r.id)" class="btn btn-ghost btn-icon p-1 text-red-400">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             TAB: GALERIE
        ══════════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'gallery'" class="p-5 space-y-4">
            <div class="grid grid-cols-3 gap-3 mb-2">
                <div class="bg-surface-700/40 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-brand-400" x-text="galleryStats.total ?? 0"></p>
                    <p class="text-xs text-surface-500">Photos total</p>
                </div>
                <div class="bg-surface-700/40 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-surface-200" x-text="galleryStats.this_month ?? 0"></p>
                    <p class="text-xs text-surface-500">Ce mois</p>
                </div>
                <div class="bg-surface-700/40 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-surface-200" x-text="formatBytes(galleryStats.total_size ?? 0)"></p>
                    <p class="text-xs text-surface-500">Espace utilisé</p>
                </div>
            </div>
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="flex gap-2">
                    <select x-model="galleryEventFilter" @change="loadGallery()" class="form-select text-sm w-auto">
                        <option value="">Toutes les photos</option>
                        <template x-for="ev in events" :key="ev.id"><option :value="ev.id" x-text="ev.name"></option></template>
                    </select>
                    <template x-if="selectedPhotos.length > 0">
                        <button @click="bulkDeletePhotos()" class="btn btn-danger btn-sm">Supprimer (<span x-text="selectedPhotos.length"></span>)</button>
                    </template>
                </div>
                <button @click="galleryUploadModal = true" class="btn btn-primary btn-sm gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajouter des photos
                </button>
            </div>
            <template x-if="galleryLoading"><div class="text-center py-10 text-surface-500"><div class="spinner mx-auto mb-2"></div>Chargement…</div></template>
            <template x-if="!galleryLoading && photos.length === 0">
                <div class="text-center py-16 text-surface-500">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p>Aucune photo dans la galerie</p>
                </div>
            </template>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                <template x-for="photo in photos" :key="photo.id">
                    <div class="relative group rounded-lg overflow-hidden bg-surface-700/40 aspect-square cursor-pointer"
                         @click="selectedPhotos.includes(photo.id) ? selectedPhotos = selectedPhotos.filter(id => id !== photo.id) : selectedPhotos.push(photo.id)">
                        <img :src="photo.url" :alt="photo.caption ?? photo.original_name" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                            <p class="text-white text-xs truncate flex-1" x-text="photo.caption ?? photo.original_name"></p>
                            <button @click.stop="deletePhoto(photo.id)" class="ml-1 text-red-400 hover:text-red-300 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                        <template x-if="selectedPhotos.includes(photo.id)">
                            <div class="absolute top-2 right-2 w-5 h-5 bg-brand-500 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
            <template x-if="galleryMeta.last_page > 1">
                <div class="flex justify-center gap-2 pt-2">
                    <button @click="galleryPage > 1 && (galleryPage--, loadGallery())" :disabled="galleryPage<=1" class="btn btn-ghost btn-sm btn-icon" :class="galleryPage<=1?'opacity-30':''">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <span class="text-sm text-surface-400 px-2 py-1" x-text="`${galleryPage} / ${galleryMeta.last_page}`"></span>
                    <button @click="galleryPage < galleryMeta.last_page && (galleryPage++, loadGallery())" :disabled="galleryPage>=galleryMeta.last_page" class="btn btn-ghost btn-sm btn-icon" :class="galleryPage>=galleryMeta.last_page?'opacity-30':''">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </template>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             TAB: BLOG
        ══════════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'blog'" class="p-5 space-y-4">
            <div class="grid grid-cols-2 gap-3 mb-1">
                <div class="bg-surface-700/40 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-brand-400" x-text="blogMeta.published ?? 0"></p>
                    <p class="text-xs text-surface-500">Articles publiés</p>
                </div>
                <div class="bg-surface-700/40 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-surface-200" x-text="blogMeta.drafts ?? 0"></p>
                    <p class="text-xs text-surface-500">Brouillons</p>
                </div>
            </div>
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="flex gap-2">
                    <select x-model="blogStatusFilter" @change="loadBlogPosts()" class="form-select text-sm w-auto">
                        <option value="">Tous les articles</option>
                        <option value="published">Publiés</option>
                        <option value="draft">Brouillons</option>
                        <option value="archived">Archivés</option>
                    </select>
                    <div class="search-wrapper w-48">
                        <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" x-model.debounce.400ms="blogSearch" class="search-input text-sm" placeholder="Rechercher…">
                    </div>
                </div>
                <button @click="openPostModal()" class="btn btn-primary btn-sm gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nouvel article
                </button>
            </div>
            <template x-if="blogLoading"><div class="text-center py-8 text-surface-500"><div class="spinner mx-auto mb-2"></div>Chargement…</div></template>
            <template x-if="!blogLoading && posts.length === 0"><div class="text-center py-10 text-surface-500">Aucun article trouvé</div></template>
            <div class="space-y-3">
                <template x-for="post in posts" :key="post.id">
                    <div class="bg-surface-700/30 rounded-lg p-4 flex items-start gap-4">
                        <template x-if="post.cover_url">
                            <img :src="post.cover_url" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                        </template>
                        <template x-if="!post.cover_url">
                            <div class="w-16 h-16 bg-surface-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                        </template>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 flex-wrap">
                                <div>
                                    <p class="font-semibold text-surface-100" x-text="post.title"></p>
                                    <p class="text-xs text-surface-500 mt-0.5" x-text="(post.author?.name ?? '') + ' · ' + post.created_at"></p>
                                </div>
                                <span class="badge text-xs flex-shrink-0" :class="post.status_color" x-text="post.status_label"></span>
                            </div>
                            <p class="text-sm text-surface-400 mt-1 line-clamp-2" x-text="post.excerpt_auto"></p>
                            <div class="flex gap-2 mt-2">
                                <button @click="openEditPostModal(post)" class="btn btn-ghost btn-sm gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Modifier
                                </button>
                                <template x-if="post.status === 'draft'">
                                    <button @click="publishPost(post.id)" class="btn btn-success btn-sm">Publier</button>
                                </template>
                                <template x-if="post.status === 'published'">
                                    <button @click="archivePost(post.id)" class="btn btn-secondary btn-sm">Archiver</button>
                                </template>
                                <template x-if="post.status === 'archived'">
                                    <button @click="publishPost(post.id)" class="btn btn-secondary btn-sm">Republier</button>
                                </template>
                                <button @click="deletePost(post.id, post.title)" class="btn btn-ghost btn-sm text-red-400">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </div>{{-- end card --}}

    {{-- ════════════════════════════════════════════════════════════════════════
         MODALS
    ════════════════════════════════════════════════════════════════════════════ --}}

    {{-- Athlete modal --}}
    <div x-show="athleteModal.open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="modal-backdrop" @keydown.escape.window="athleteModal.open=false" style="display:none">
        <div @click.stop x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="modal max-w-2xl">
            <div class="modal-header">
                <h3 class="text-base font-bold text-surface-50" x-text="athleteModal.editing ? 'Modifier l\'athlète' : 'Ajouter un athlète'"></h3>
                <button @click="athleteModal.open=false" class="btn btn-ghost btn-icon p-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="modal-body">
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group"><label class="form-label">Prénom</label><input type="text" x-model="athleteForm.first_name" required class="form-input"></div>
                    <div class="form-group"><label class="form-label">Nom</label><input type="text" x-model="athleteForm.last_name" required class="form-input"></div>
                    <div class="form-group"><label class="form-label">Date de naissance</label><input type="date" x-model="athleteForm.birth_date" required class="form-input"></div>
                    <div class="form-group"><label class="form-label">Genre</label><select x-model="athleteForm.gender" required class="form-select"><option value="M">Masculin</option><option value="F">Féminin</option></select></div>
                    <div class="form-group"><label class="form-label">Poids (kg)</label><input type="number" step="0.1" x-model="athleteForm.weight" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Club</label><input type="text" x-model="athleteForm.club" required class="form-input"></div>
                    <div class="form-group"><label class="form-label">Événement</label>
                        <select x-model="athleteForm.event_id" required class="form-select">
                            <option value="">Sélectionner…</option>
                            <template x-for="ev in events" :key="ev.id"><option :value="ev.id" x-text="ev.name"></option></template>
                        </select>
                    </div>
                    <div class="form-group"><label class="form-label">N° Licence</label><input type="text" x-model="athleteForm.license_number" class="form-input"></div>
                    <div class="form-group col-span-2"><label class="form-label">Nationalité</label><input type="text" x-model="athleteForm.nationality" class="form-input" placeholder="ex: Sénégalais"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="athleteModal.open=false" class="btn btn-secondary">Annuler</button>
                <button @click="saveAthlete()" :disabled="athleteModal.saving" class="btn btn-primary">
                    <div x-show="athleteModal.saving" class="spinner w-4 h-4"></div>
                    <span x-text="athleteModal.saving ? 'Enregistrement…' : (athleteModal.editing ? 'Mettre à jour' : 'Ajouter')"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Event modal --}}
    <div x-show="eventModal.open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="modal-backdrop" @keydown.escape.window="eventModal.open=false" style="display:none">
        <div @click.stop class="modal">
            <div class="modal-header">
                <h3 class="text-base font-bold text-surface-50" x-text="eventModal.editing ? 'Modifier l\'événement' : 'Nouvel événement'"></h3>
                <button @click="eventModal.open=false" class="btn btn-ghost btn-icon p-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="modal-body space-y-4">
                <div class="form-group"><label class="form-label">Nom</label><input type="text" x-model="eventForm.name" required class="form-input" placeholder="ex: Championnat National 2026"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group"><label class="form-label">Date début</label><input type="date" x-model="eventForm.start_date" required class="form-input"></div>
                    <div class="form-group"><label class="form-label">Date fin</label><input type="date" x-model="eventForm.end_date" class="form-input"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group"><label class="form-label">Type</label>
                        <select x-model="eventForm.type" class="form-select"><option value="kyorugi">Kyorugi</option><option value="poomsae">Poomsae</option><option value="mixed">Mixte</option><option value="other">Autre</option></select>
                    </div>
                    <div class="form-group"><label class="form-label">Statut</label>
                        <select x-model="eventForm.status" class="form-select"><option value="upcoming">À venir</option><option value="open">Inscriptions ouvertes</option><option value="closed">Fermées</option><option value="ongoing">En cours</option><option value="finished">Terminé</option><option value="cancelled">Annulé</option></select>
                    </div>
                </div>
                <div class="form-group"><label class="form-label">Lieu</label><input type="text" x-model="eventForm.location" class="form-input" placeholder="ex: Dakar Arena"></div>
                <div class="form-group"><label class="form-label">Frais d'inscription (FCFA)</label><input type="number" x-model="eventForm.registration_fee" class="form-input"></div>
                <div class="form-group"><label class="form-label">Description</label><textarea x-model="eventForm.description" rows="3" class="form-input"></textarea></div>
                <div class="form-group">
                    <label class="form-label">Image de couverture</label>
                    <template x-if="eventModal.editing && eventForm.cover_url && !eventImageFile">
                        <img :src="eventForm.cover_url" class="w-full h-32 object-cover rounded-lg mb-2">
                    </template>
                    <input type="file" accept="image/jpeg,image/jpg,image/png,image/webp"
                           @change="eventImageFile = $event.target.files[0] || null"
                           class="form-input p-2 text-sm">
                    <template x-if="eventImageFile">
                        <p class="text-xs text-surface-500 mt-1" x-text="eventImageFile.name"></p>
                    </template>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="eventModal.open=false" class="btn btn-secondary">Annuler</button>
                <button @click="saveEvent()" :disabled="eventModal.saving" class="btn btn-primary">
                    <div x-show="eventModal.saving" class="spinner w-4 h-4"></div>
                    <span x-text="eventModal.saving ? 'Enregistrement…' : (eventModal.editing ? 'Mettre à jour' : 'Créer')"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- User create modal --}}
    <div x-show="userModal.open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="modal-backdrop" @keydown.escape.window="userModal.open=false" style="display:none">
        <div @click.stop class="modal">
            <div class="modal-header">
                <h3 class="text-base font-bold text-surface-50">Nouvel utilisateur</h3>
                <button @click="userModal.open=false" class="btn btn-ghost btn-icon p-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="modal-body space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group col-span-2"><label class="form-label">Nom complet</label><input type="text" x-model="userForm.name" required class="form-input"></div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" x-model="userForm.email" required class="form-input"></div>
                    <div class="form-group"><label class="form-label">Mot de passe</label><input type="password" x-model="userForm.password" required class="form-input" placeholder="min. 8 caractères"></div>
                    <div class="form-group"><label class="form-label">Rôle</label>
                        <select x-model="userForm.role" class="form-select"><option value="coach">Coach</option><option value="financial">Financier</option><option value="technical">Technique</option><option value="admin">Admin</option></select>
                    </div>
                    <div class="form-group"><label class="form-label">Club</label><input type="text" x-model="userForm.club" class="form-input"></div>
                    <div class="form-group col-span-2"><label class="form-label">Téléphone</label><input type="text" x-model="userForm.phone" class="form-input"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="userModal.open=false" class="btn btn-secondary">Annuler</button>
                <button @click="saveUser()" :disabled="userModal.saving" class="btn btn-primary">
                    <div x-show="userModal.saving" class="spinner w-4 h-4"></div>
                    <span x-text="userModal.saving ? 'Création…' : 'Créer l\'utilisateur'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Ranking add modal --}}
    <div x-show="rankingModal.open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="modal-backdrop" @keydown.escape.window="rankingModal.open=false" style="display:none">
        <div @click.stop class="modal">
            <div class="modal-header">
                <h3 class="text-base font-bold text-surface-50">Ajouter une entrée de classement</h3>
                <button @click="rankingModal.open=false" class="btn btn-ghost btn-icon p-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="modal-body space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group col-span-2"><label class="form-label">Événement</label>
                        <select x-model="rankingForm.event_id" required class="form-select"><option value="">Sélectionner…</option><template x-for="ev in events" :key="ev.id"><option :value="ev.id" x-text="ev.name"></option></template></select>
                    </div>
                    <div class="form-group col-span-2"><label class="form-label">Athlète (ID)</label><input type="number" x-model="rankingForm.athlete_id" required class="form-input" placeholder="ID de l'athlète"></div>
                    <div class="form-group col-span-2"><label class="form-label">Catégorie</label><input type="text" x-model="rankingForm.category" required class="form-input" placeholder="ex: Senior|M|-68kg"></div>
                    <div class="form-group"><label class="form-label">Position</label><input type="number" x-model="rankingForm.position" min="1" class="form-input" placeholder="1, 2, 3…"></div>
                    <div class="form-group"><label class="form-label">Saison</label><input type="number" x-model="rankingForm.season" class="form-input" :placeholder="new Date().getFullYear()"></div>
                    <div class="form-group"><label class="form-label">Victoires</label><input type="number" x-model="rankingForm.wins" min="0" class="form-input" value="0"></div>
                    <div class="form-group"><label class="form-label">Défaites</label><input type="number" x-model="rankingForm.losses" min="0" class="form-input" value="0"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="rankingModal.open=false" class="btn btn-secondary">Annuler</button>
                <button @click="saveRanking()" :disabled="rankingModal.saving" class="btn btn-primary">
                    <div x-show="rankingModal.saving" class="spinner w-4 h-4"></div>
                    <span x-text="rankingModal.saving ? 'Enregistrement…' : 'Ajouter'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Recalculate ranking modal --}}
    <div x-show="recalcModal.open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="modal-backdrop" @keydown.escape.window="recalcModal.open=false" style="display:none">
        <div @click.stop class="modal max-w-sm">
            <div class="modal-header">
                <h3 class="text-base font-bold text-surface-50">Recalculer depuis les tirages</h3>
                <button @click="recalcModal.open=false" class="btn btn-ghost btn-icon p-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="modal-body space-y-3">
                <p class="text-sm text-surface-400">Recalcule automatiquement le classement en analysant les résultats des tirages générés pour l'événement sélectionné.</p>
                <div class="form-group"><label class="form-label">Événement</label>
                    <select x-model="recalcEventId" class="form-select"><option value="">Sélectionner…</option><template x-for="ev in events" :key="ev.id"><option :value="ev.id" x-text="ev.name"></option></template></select>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="recalcModal.open=false" class="btn btn-secondary">Annuler</button>
                <button @click="recalculateRankings()" :disabled="recalcModal.loading || !recalcEventId" class="btn btn-primary">
                    <div x-show="recalcModal.loading" class="spinner w-4 h-4"></div>
                    <span x-text="recalcModal.loading ? 'Calcul…' : 'Recalculer'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Gallery upload modal --}}
    <div x-show="galleryUploadModal" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="modal-backdrop" @keydown.escape.window="galleryUploadModal=false" style="display:none">
        <div @click.stop class="modal">
            <div class="modal-header">
                <h3 class="text-base font-bold text-surface-50">Ajouter des photos</h3>
                <button @click="galleryUploadModal=false" class="btn btn-ghost btn-icon p-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="modal-body space-y-4">
                <div class="form-group">
                    <label class="form-label">Photos (max 20, 5 Mo chacune)</label>
                    <input type="file" multiple accept="image/jpeg,image/jpg,image/png,image/webp"
                           @change="galleryFiles = Array.from($event.target.files)"
                           class="form-input p-2 text-sm">
                    <template x-if="galleryFiles.length > 0">
                        <p class="text-xs text-surface-500 mt-1" x-text="galleryFiles.length + ' fichier(s) sélectionné(s)'"></p>
                    </template>
                </div>
                <div class="form-group"><label class="form-label">Événement associé (optionnel)</label>
                    <select x-model="galleryUploadEventId" class="form-select"><option value="">Aucun</option><template x-for="ev in events" :key="ev.id"><option :value="ev.id" x-text="ev.name"></option></template></select>
                </div>
                <div class="form-group"><label class="form-label">Légende (optionnel)</label><input type="text" x-model="galleryUploadCaption" class="form-input" placeholder="Description des photos…"></div>
            </div>
            <div class="modal-footer">
                <button @click="galleryUploadModal=false" class="btn btn-secondary">Annuler</button>
                <button @click="uploadPhotos()" :disabled="galleryUploading || galleryFiles.length === 0" class="btn btn-primary">
                    <div x-show="galleryUploading" class="spinner w-4 h-4"></div>
                    <span x-text="galleryUploading ? 'Upload en cours…' : 'Uploader'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Blog post modal --}}
    <div x-show="postModal.open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="modal-backdrop" @keydown.escape.window="postModal.open=false" style="display:none">
        <div @click.stop class="modal max-w-3xl">
            <div class="modal-header">
                <h3 class="text-base font-bold text-surface-50" x-text="postModal.editing ? 'Modifier l\'article' : 'Nouvel article'"></h3>
                <button @click="postModal.open=false" class="btn btn-ghost btn-icon p-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="modal-body space-y-4">
                <div class="form-group"><label class="form-label">Titre</label><input type="text" x-model="postForm.title" required class="form-input" placeholder="Titre de l'article…"></div>
                <div class="form-group"><label class="form-label">Contenu</label><textarea x-model="postForm.content" rows="10" required class="form-input font-mono text-sm" placeholder="Contenu de l'article…"></textarea></div>
                <div class="form-group"><label class="form-label">Extrait (optionnel)</label><textarea x-model="postForm.excerpt" rows="2" class="form-input" placeholder="Résumé court affiché dans les listes…"></textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group"><label class="form-label">Statut</label>
                        <select x-model="postForm.status" class="form-select"><option value="draft">Brouillon</option><option value="published">Publié</option></select>
                    </div>
                    <div class="form-group"><label class="form-label">Image de couverture</label><input type="file" accept="image/*" @change="postCoverFile = $event.target.files[0]" class="form-input p-2 text-sm"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="postModal.open=false" class="btn btn-secondary">Annuler</button>
                <button @click="savePost()" :disabled="postModal.saving" class="btn btn-primary">
                    <div x-show="postModal.saving" class="spinner w-4 h-4"></div>
                    <span x-text="postModal.saving ? 'Enregistrement…' : (postModal.editing ? 'Mettre à jour' : 'Créer')"></span>
                </button>
            </div>
        </div>
    </div>

</div>{{-- end x-data --}}

@push('scripts')
<script>
function technicalDashboard() {
    return {
        // ── Navigation ──────────────────────────────────────────────────────
        tab: 'athletes',
        tabs: [
            { id: 'events',     icon: '📅', label: 'Événements',   badge: null },
            { id: 'athletes',   icon: '🥋', label: 'Athlètes',     badge: null },
            { id: 'coaches',    icon: '👤', label: 'Coachs',       badge: null },
            { id: 'users',      icon: '⚙️', label: 'Utilisateurs', badge: null },
            { id: 'categories', icon: '🏷️', label: 'Catégories',   badge: null },
            { id: 'draws',      icon: '🎯', label: 'Tirages',      badge: null },
            { id: 'exports',    icon: '📥', label: 'Exports',      badge: null },
            { id: 'ranking',    icon: '🏆', label: 'Classement',   badge: null },
            { id: 'gallery',    icon: '🖼️', label: 'Galerie',      badge: null },
            { id: 'blog',       icon: '📝', label: 'Blog',         badge: null },
        ],

        // ── Global ──────────────────────────────────────────────────────────
        stats: {},
        events: [],

        // ── Athletes tab ────────────────────────────────────────────────────
        athletes: [], loading: false, page: 1, athletesMeta: {},
        filters: { search: '', event_id: '', registration_status: '', age_category: '', gender: '', weight_category: '', club: '' },
        selected: [],
        athleteModal: { open: false, editing: false, saving: false },
        athleteForm: {},

        // ── Events tab ──────────────────────────────────────────────────────
        eventsLoading: false, eventSearch: '',
        eventModal: { open: false, editing: false, saving: false },
        eventForm: { name:'', start_date:'', end_date:'', type:'kyorugi', status:'upcoming', location:'', registration_fee:'', description:'' },
        eventImageFile: null,

        // ── Coaches tab ─────────────────────────────────────────────────────
        coaches: [], coachesLoading: false, coachSearch: '', selectedCoaches: [],

        // ── Users tab ───────────────────────────────────────────────────────
        isAdmin: {{ auth()->user()->isAdmin() ? 'true' : 'false' }},
        users: [], usersLoading: false, userSearch: '', userRoleFilter: '',
        userModal: { open: false, saving: false },
        userForm: { name:'', email:'', password:'', role:'coach', club:'', phone:'' },

        // ── Categories tab ──────────────────────────────────────────────────
        catGender: '', catAge: '',
        allCategories: [
            { age:'Benjamin', gender:'M', weights:['-23kg','-26kg','-30kg','-35kg','-40kg','-45kg','+45kg'] },
            { age:'Benjamin', gender:'F', weights:['-23kg','-26kg','-30kg','-33kg','-37kg','-41kg','+41kg'] },
            { age:'Minime',   gender:'M', weights:['-33kg','-37kg','-41kg','-45kg','-49kg','-53kg','-57kg','-61kg','+61kg'] },
            { age:'Minime',   gender:'F', weights:['-29kg','-33kg','-37kg','-41kg','-44kg','-47kg','-51kg','-55kg','+55kg'] },
            { age:'Cadet',    gender:'M', weights:['-41kg','-45kg','-49kg','-53kg','-57kg','-61kg','-65kg','-70kg','+70kg'] },
            { age:'Cadet',    gender:'F', weights:['-37kg','-41kg','-44kg','-47kg','-51kg','-55kg','-59kg','-63kg','+63kg'] },
            { age:'Junior',   gender:'M', weights:['-48kg','-51kg','-55kg','-59kg','-63kg','-68kg','-73kg','-78kg','+78kg'] },
            { age:'Junior',   gender:'F', weights:['-42kg','-44kg','-46kg','-49kg','-52kg','-55kg','-59kg','-63kg','+63kg'] },
            { age:'Senior',   gender:'M', weights:['-54kg','-58kg','-63kg','-68kg','-74kg','-80kg','-87kg','+87kg'] },
            { age:'Senior',   gender:'F', weights:['-46kg','-49kg','-53kg','-57kg','-62kg','-67kg','-73kg','+73kg'] },
        ],

        // ── Draws tab ───────────────────────────────────────────────────────
        draws: [], drawsLoading: false, drawsEventFilter: '',
        drawForm: { event_id:'', category_key:'' },
        drawCategories: [], drawGenerating: false,

        // ── Exports tab ─────────────────────────────────────────────────────
        exportEventId: '', exportStatus: '', exportAgeCategory: '', exportGender: '', exportWeightCategory: '', exportClub: '',

        // ── Ranking tab ─────────────────────────────────────────────────────
        rankings: [], rankingLoading: false,
        rankingSeason: new Date().getFullYear(),
        rankingEventId: '', rankingCategory: '',
        rankingCategoryOptions: [],
        rankingSeasons: Array.from({length: 5}, (_, i) => new Date().getFullYear() - i),
        rankingModal: { open: false, saving: false },
        rankingForm: { athlete_id:'', event_id:'', category:'', position:'', season: new Date().getFullYear(), wins:0, losses:0 },
        recalcModal: { open: false, loading: false }, recalcEventId: '',

        // ── Gallery tab ─────────────────────────────────────────────────────
        photos: [], galleryLoading: false, selectedPhotos: [],
        galleryStats: {}, galleryMeta: {}, galleryPage: 1, galleryEventFilter: '',
        galleryUploadModal: false, galleryFiles: [], galleryUploading: false,
        galleryUploadEventId: '', galleryUploadCaption: '',

        // ── Blog tab ────────────────────────────────────────────────────────
        posts: [], blogLoading: false, blogStatusFilter: '', blogSearch: '',
        blogMeta: {}, blogPage: 1,
        postModal: { open: false, editing: false, saving: false },
        postForm: { title:'', content:'', excerpt:'', status:'draft' },
        postCoverFile: null,

        // ════════════════════════════════════════════════════════════════════
        // INIT
        // ════════════════════════════════════════════════════════════════════
        async init() {
            await Promise.all([this.loadStats(), this.loadEvents()]);
            this.switchTab('athletes');
            this.$watch('filters.search', () => { this.page = 1; this.loadAthletes(); });
            this.$watch('userSearch',     () => this.loadUsers());
            this.$watch('blogSearch',     () => this.loadBlogPosts());
        },

        switchTab(id) {
            this.tab = id;
            this.loadTabData(id);
        },

        async loadTabData(id) {
            const map = {
                athletes:   () => this.loadAthletes(),
                events:     () => this.loadEventsTable(),
                coaches:    () => this.loadCoaches(),
                users:      () => this.loadUsers(),
                categories: () => {},
                draws:      () => this.loadDrawsList(),
                exports:    () => {},
                ranking:    () => this.loadRankings(),
                gallery:    () => Promise.all([this.loadGallery(), this.loadGalleryStats()]),
                blog:       () => this.loadBlogPosts(),
            };
            await (map[id] ?? (() => {}))();
        },

        // ════════════════════════════════════════════════════════════════════
        // COMPUTED
        // ════════════════════════════════════════════════════════════════════
        get filteredEvents() {
            const s = this.eventSearch.toLowerCase();
            return this.events.filter(e => !s || e.name.toLowerCase().includes(s));
        },
        get filteredCoaches() {
            const s = this.coachSearch.toLowerCase();
            return this.coaches.filter(c => !s || c.name.toLowerCase().includes(s) || (c.email ?? '').toLowerCase().includes(s));
        },
        get filteredCategories() {
            return this.allCategories.filter(g =>
                (!this.catGender || g.gender === this.catGender) &&
                (!this.catAge   || g.age === this.catAge)
            );
        },

        // Weight categories for athletes filter (reactive on age_category + gender)
        get weightCategoryOptions() {
            const age    = this.filters.age_category;
            const gender = this.filters.gender;
            if (!age) return [];
            if (gender) {
                const g = this.allCategories.find(c => c.age === age && c.gender === gender);
                return g ? g.weights : [];
            }
            // Both genders merged, unique, sorted
            const all = this.allCategories.filter(c => c.age === age).flatMap(c => c.weights);
            return [...new Set(all)].sort((a, b) => parseInt(a) - parseInt(b));
        },

        // Weight categories for export filter (reactive on exportAgeCategory + exportGender)
        get exportWeightOptions() {
            const age    = this.exportAgeCategory;
            const gender = this.exportGender;
            if (!age) return [];
            if (gender) {
                const g = this.allCategories.find(c => c.age === age && c.gender === gender);
                return g ? g.weights : [];
            }
            const all = this.allCategories.filter(c => c.age === age).flatMap(c => c.weights);
            return [...new Set(all)].sort((a, b) => parseInt(a) - parseInt(b));
        },

        // ════════════════════════════════════════════════════════════════════
        // STATS
        // ════════════════════════════════════════════════════════════════════
        async loadStats() {
            const dash = await api.get('/dashboard');
            this.stats = dash.data ?? {};
        },

        // ════════════════════════════════════════════════════════════════════
        // EVENTS
        // ════════════════════════════════════════════════════════════════════
        async loadEvents() {
            const data = await api.get('/api/events');
            this.events = data.data ?? [];
            this.tabs[0].badge = this.events.length || null;
        },
        async loadEventsTable() {
            this.eventsLoading = true;
            await this.loadEvents();
            this.eventsLoading = false;
        },
        openEventModal() {
            this.eventForm = { name:'', start_date:'', end_date:'', type:'kyorugi', status:'upcoming', location:'', registration_fee:'', description:'' };
            this.eventImageFile = null;
            this.eventModal = { open: true, editing: false, saving: false };
        },
        openEditEventModal(ev) {
            this.eventForm = { ...ev };
            // Convert d/m/Y → YYYY-MM-DD for <input type="date">
            const toISO = s => {
                if (!s) return '';
                const p = s.split('/');
                return p.length === 3 ? `${p[2]}-${p[1].padStart(2,'0')}-${p[0].padStart(2,'0')}` : s;
            };
            this.eventForm.start_date = toISO(ev.start_date);
            this.eventForm.end_date   = toISO(ev.end_date);
            this.eventImageFile = null;
            this.eventModal = { open: true, editing: true, saving: false };
        },
        async saveEvent() {
            this.eventModal.saving = true;
            try {
                const form = new FormData();
                Object.entries(this.eventForm).forEach(([k, v]) => {
                    if (k === 'registration_fee') {
                        // Toujours inclure pour permettre la mise à null (valeur vide = null côté serveur)
                        form.append(k, v ?? '');
                    } else if (v !== null && v !== undefined && v !== '') {
                        form.append(k, v);
                    }
                });
                if (this.eventImageFile) form.append('cover_image', this.eventImageFile);
                if (this.eventModal.editing) form.append('_method', 'PUT');

                const url = this.eventModal.editing ? `/api/events/${this.eventForm.id}` : '/api/events';
                const r   = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: form,
                });
                const res = await r.json();

                if (res.success) {
                    $store.toast.success(res.message ?? 'Événement enregistré.');
                    this.eventModal.open = false;
                    await this.loadEvents();
                } else {
                    const detail = res.errors ? Object.values(res.errors)[0]?.[0] : null;
                    $store.toast.error(detail ?? res.message ?? 'Erreur lors de la sauvegarde.');
                }
            } catch (e) {
                $store.toast.error('Erreur réseau, veuillez réessayer.');
            } finally { this.eventModal.saving = false; }
        },
        async deleteEvent(id, name) {
            if (!confirm(`Supprimer l'événement "${name}" ?`)) return;
            const res = await api.delete(`/api/events/${id}`);
            if (res.success) { $store.toast.success(res.message); await this.loadEvents(); }
        },

        // ════════════════════════════════════════════════════════════════════
        // ATHLETES
        // ════════════════════════════════════════════════════════════════════
        async loadAthletes() {
            this.loading = true;
            const params = { ...this.filters, page: this.page, per_page: 25 };
            const data = await api.get('/api/athletes', params);
            this.athletes = data.data ?? [];
            this.athletesMeta = data.meta ?? {};
            this.tabs[1].badge = (this.athletesMeta.total) || null;
            this.loading = false;
        },
        toggleSelectAll(checked) { this.selected = checked ? this.athletes.map(a => a.id) : []; },
        openAddModal() {
            this.athleteForm = { first_name:'', last_name:'', birth_date:'', gender:'M', weight:'', club:'', event_id:'', license_number:'', nationality:'' };
            this.athleteModal = { open: true, editing: false, saving: false };
        },
        openEditModal(athlete) { this.athleteForm = { ...athlete }; this.athleteModal = { open: true, editing: true, saving: false }; },
        async saveAthlete() {
            this.athleteModal.saving = true;
            try {
                const res = this.athleteModal.editing
                    ? await api.put(`/api/athletes/${this.athleteForm.id}`, this.athleteForm)
                    : await api.post('/api/athletes', this.athleteForm);
                if (res.success) {
                    $store.toast.success(res.message ?? 'Athlète enregistré.');
                    this.athleteModal.open = false;
                    this.loadAthletes(); this.loadStats();
                } else {
                    const detail = res.errors ? Object.values(res.errors)[0]?.[0] : null;
                    $store.toast.error(detail ?? res.message ?? 'Erreur.');
                }
            } catch (e) {
                $store.toast.error('Erreur réseau, veuillez réessayer.');
            } finally { this.athleteModal.saving = false; }
        },
        async validateAthlete(id) {
            const res = await api.post(`/api/athletes/${id}/validate`);
            if (res.success) { $store.toast.success(res.message); this.loadAthletes(); }
        },
        async deleteAthlete(id, name) {
            if (!confirm(`Supprimer ${name} ?`)) return;
            const res = await api.delete(`/api/athletes/${id}`);
            if (res.success) { $store.toast.success(res.message); this.loadAthletes(); this.loadStats(); }
        },
        async bulkValidate() {
            const res = await api.post('/api/athletes/bulk-validate', { ids: this.selected });
            if (res.success) { $store.toast.success(res.message); this.selected = []; this.loadAthletes(); }
        },
        async bulkDelete() {
            if (!confirm(`Supprimer ${this.selected.length} athlète(s) sélectionné(s) ?`)) return;
            const res = await api.post('/api/athletes/bulk-delete', { ids: this.selected });
            if (res.success) { $store.toast.success(res.message); this.selected = []; this.loadAthletes(); this.loadStats(); }
        },
        async validateByClub() {
            const club = this.filters.club;
            if (!club) return;
            if (!confirm(`Valider tous les athlètes du club "${club}" ?`)) return;
            const payload = { club };
            if (this.filters.event_id) payload.event_id = this.filters.event_id;
            const res = await api.post('/api/athletes/validate-by-club', payload);
            if (res.success) { $store.toast.success(res.message); this.loadAthletes(); this.loadStats(); }
        },
        async deleteByClub() {
            const club = this.filters.club;
            if (!club) return;
            if (!confirm(`Supprimer TOUS les athlètes du club "${club}" ? Cette action est irréversible.`)) return;
            const payload = { club };
            if (this.filters.event_id) payload.event_id = this.filters.event_id;
            const res = await api.post('/api/athletes/delete-by-club', payload);
            if (res.success) { $store.toast.success(res.message); this.filters.club = ''; this.loadAthletes(); this.loadStats(); }
        },

        // ════════════════════════════════════════════════════════════════════
        // COACHES
        // ════════════════════════════════════════════════════════════════════
        async loadCoaches() {
            this.coachesLoading = true;
            const data = await api.get('/api/coaches');
            this.coaches = data.data ?? [];
            const pending = this.coaches.filter(c => !c.is_validated && c.account_status !== 'rejected').length;
            this.tabs[2].badge = pending || null;
            this.coachesLoading = false;
        },
        toggleSelectAllCoaches(checked) { this.selectedCoaches = checked ? this.coaches.map(c => c.id) : []; },
        async validateCoach(id) {
            const res = await api.post(`/api/coaches/${id}/validate`);
            if (res.success) { $store.toast.success(res.message); this.loadCoaches(); }
        },
        async rejectCoach(id) {
            const res = await api.post(`/api/coaches/${id}/reject`);
            if (res.success) { $store.toast.success(res.message); this.loadCoaches(); }
        },
        async deleteCoach(id, name) {
            if (!confirm(`Supprimer le coach ${name} et tous ses athlètes ?`)) return;
            const res = await api.delete(`/api/coaches/${id}`);
            if (res.success) { $store.toast.success(res.message); this.loadCoaches(); }
        },
        async bulkValidateCoaches() {
            const res = await api.post('/api/coaches/bulk-validate', { ids: this.selectedCoaches });
            if (res.success) { $store.toast.success(res.message); this.selectedCoaches = []; this.loadCoaches(); }
        },
        async bulkRejectCoaches() {
            const res = await api.post('/api/coaches/bulk-reject', { ids: this.selectedCoaches });
            if (res.success) { $store.toast.success(res.message); this.selectedCoaches = []; this.loadCoaches(); }
        },

        // ════════════════════════════════════════════════════════════════════
        // USERS
        // ════════════════════════════════════════════════════════════════════
        async loadUsers() {
            this.usersLoading = true;
            const params = {};
            if (this.userSearch) params.search = this.userSearch;
            if (this.userRoleFilter) params.role = this.userRoleFilter;
            const data = await api.get('/api/users', params);
            this.users = data.data ?? [];
            this.usersLoading = false;
        },
        openUserModal() {
            this.userForm = { name:'', email:'', password:'', role:'coach', club:'', phone:'' };
            this.userModal = { open: true, saving: false };
        },
        async saveUser() {
            this.userModal.saving = true;
            try {
                const res = await api.post('/api/users', this.userForm);
                if (res.success) {
                    $store.toast.success(res.message);
                    this.userModal.open = false;
                    this.loadUsers();
                } else {
                    const detail = res.errors ? Object.values(res.errors)[0]?.[0] : null;
                    $store.toast.error(detail ?? res.message ?? 'Erreur.');
                }
            } catch (e) {
                $store.toast.error('Erreur réseau, veuillez réessayer.');
            } finally { this.userModal.saving = false; }
        },
        async changeUserRole(id, role) {
            const res = await api.put(`/api/users/${id}/role`, { role });
            if (res.success) { $store.toast.success(res.message); this.loadUsers(); } else { $store.toast.error(res.message); this.loadUsers(); }
        },
        async toggleUserValidation(id) {
            const res = await api.post(`/api/users/${id}/toggle-validation`);
            if (res.success) { $store.toast.success(res.message); this.loadUsers(); }
        },
        async deleteUser(id, name) {
            if (!confirm(`Supprimer l'utilisateur ${name} ?`)) return;
            const res = await api.delete(`/api/users/${id}`);
            if (res.success) { $store.toast.success(res.message); this.loadUsers(); }
        },
        async sendPasswordReset(id, name, email) {
            if (!confirm(`Envoyer un email de réinitialisation de mot de passe à ${name} (${email}) ?`)) return;
            const res = await api.post(`/api/users/${id}/send-reset`, {});
            if (res.success) { $store.toast.success(res.message); }
            else { $store.toast.error(res.message ?? 'Erreur lors de l\'envoi.'); }
        },

        // ════════════════════════════════════════════════════════════════════
        // DRAWS
        // ════════════════════════════════════════════════════════════════════
        async loadDrawsList() {
            this.drawsLoading = true;
            const params = this.drawsEventFilter ? { event_id: this.drawsEventFilter } : {};
            const data = await api.get('/api/draws/by-event', params);
            this.draws = data.data ?? [];
            this.drawsLoading = false;
        },
        async loadDrawCategories() {
            if (!this.drawForm.event_id) { this.drawCategories = []; return; }
            const data = await api.get('/api/athletes/categories-by-event', { event_id: this.drawForm.event_id });
            this.drawCategories = data.data ?? [];
        },
        async generateDraw() {
            this.drawGenerating = true;
            try {
                const [age_category, gender, weight_category] = this.drawForm.category_key.split('|');
                const res = await api.post('/api/draws/generate', { event_id: this.drawForm.event_id, age_category, gender, weight_category });
                if (res.success) { $store.toast.success('Tirage généré !'); this.loadDrawsList(); }
                else { $store.toast.error(res.message ?? 'Erreur.'); }
            } finally { this.drawGenerating = false; }
        },
        async deleteDraw(id) {
            if (!confirm('Supprimer ce tirage ?')) return;
            const res = await api.delete(`/api/draws/${id}`);
            if (res.success) { $store.toast.success(res.message); this.loadDrawsList(); }
        },

        // ════════════════════════════════════════════════════════════════════
        // EXPORTS
        // ════════════════════════════════════════════════════════════════════
        buildExportQuery() {
            const p = [];
            if (this.exportEventId)        p.push('event_id='             + encodeURIComponent(this.exportEventId));
            if (this.exportStatus)         p.push('registration_status='  + encodeURIComponent(this.exportStatus));
            if (this.exportAgeCategory)    p.push('age_category='         + encodeURIComponent(this.exportAgeCategory));
            if (this.exportGender)         p.push('gender='               + encodeURIComponent(this.exportGender));
            if (this.exportWeightCategory) p.push('weight_category='      + encodeURIComponent(this.exportWeightCategory));
            if (this.exportClub)           p.push('club='                 + encodeURIComponent(this.exportClub));
            return p.length ? '?' + p.join('&') : '';
        },

        // ════════════════════════════════════════════════════════════════════
        // RANKING
        // ════════════════════════════════════════════════════════════════════
        async loadRankings() {
            this.rankingLoading = true;
            const params = { season: this.rankingSeason };
            if (this.rankingEventId) params.event_id = this.rankingEventId;
            if (this.rankingCategory) params.category = this.rankingCategory;
            const data = await api.get('/api/rankings', params);
            this.rankings = data.data ?? [];
            // Build category options from results
            const cats = [...new Set(this.rankings.map(r => r.category))].sort();
            this.rankingCategoryOptions = cats;
            this.rankingLoading = false;
        },
        openAddRankingModal() {
            this.rankingForm = { athlete_id:'', event_id:'', category:'', position:'', season: this.rankingSeason, wins:0, losses:0 };
            this.rankingModal = { open: true, saving: false };
        },
        async saveRanking() {
            this.rankingModal.saving = true;
            try {
                const res = await api.post('/api/rankings', this.rankingForm);
                if (res.success) {
                    $store.toast.success(res.message);
                    this.rankingModal.open = false;
                    this.loadRankings();
                } else {
                    const detail = res.errors ? Object.values(res.errors)[0]?.[0] : null;
                    $store.toast.error(detail ?? res.message ?? 'Erreur.');
                }
            } catch (e) {
                $store.toast.error('Erreur réseau, veuillez réessayer.');
            } finally { this.rankingModal.saving = false; }
        },
        async deleteRanking(id) {
            const res = await api.delete(`/api/rankings/${id}`);
            if (res.success) { $store.toast.success(res.message); this.loadRankings(); }
        },
        openRecalculateModal() { this.recalcEventId = ''; this.recalcModal = { open: true, loading: false }; },
        async recalculateRankings() {
            this.recalcModal.loading = true;
            try {
                const res = await api.post('/api/rankings/recalculate', { event_id: this.recalcEventId });
                if (res.success) {
                    $store.toast.success(res.message);
                    this.recalcModal.open = false;
                    this.rankingEventId = this.recalcEventId;
                    this.loadRankings();
                } else { $store.toast.error(res.message ?? 'Erreur.'); }
            } finally { this.recalcModal.loading = false; }
        },

        // ════════════════════════════════════════════════════════════════════
        // GALLERY
        // ════════════════════════════════════════════════════════════════════
        async loadGallery() {
            this.galleryLoading = true;
            const params = { page: this.galleryPage, per_page: 30 };
            if (this.galleryEventFilter) params.event_id = this.galleryEventFilter;
            const data = await api.get('/api/gallery', params);
            this.photos   = (data.data ?? []).map(p => ({ ...p, url: p.url ?? p.path }));
            this.galleryMeta = data.meta ?? {};
            this.galleryLoading = false;
        },
        async loadGalleryStats() {
            const data = await api.get('/api/gallery/stats');
            this.galleryStats = data.data ?? {};
        },
        async uploadPhotos() {
            if (!this.galleryFiles.length) return;
            this.galleryUploading = true;
            try {
                const form = new FormData();
                this.galleryFiles.forEach(f => form.append('photos[]', f));
                if (this.galleryUploadEventId) form.append('event_id', this.galleryUploadEventId);
                if (this.galleryUploadCaption) form.append('caption', this.galleryUploadCaption);

                const res = await fetch('/api/gallery', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: form,
                }).then(r => r.json());

                if (res.success) {
                    $store.toast.success(res.message);
                    this.galleryUploadModal = false;
                    this.galleryFiles = [];
                    this.loadGallery();
                    this.loadGalleryStats();
                } else { $store.toast.error(res.message ?? 'Erreur.'); }
            } finally { this.galleryUploading = false; }
        },
        async deletePhoto(id) {
            if (!confirm('Supprimer cette photo ?')) return;
            const res = await api.delete(`/api/gallery/${id}`);
            if (res.success) { $store.toast.success(res.message); this.loadGallery(); this.loadGalleryStats(); }
        },
        async bulkDeletePhotos() {
            if (!confirm(`Supprimer ${this.selectedPhotos.length} photo(s) ?`)) return;
            const res = await api.post('/api/gallery/bulk-delete', { ids: this.selectedPhotos });
            if (res.success) { $store.toast.success(res.message); this.selectedPhotos = []; this.loadGallery(); this.loadGalleryStats(); }
        },
        formatBytes(b) {
            if (!b) return '0 Mo';
            if (b < 1024) return b + ' o';
            if (b < 1048576) return (b/1024).toFixed(1) + ' Ko';
            return (b/1048576).toFixed(1) + ' Mo';
        },

        // ════════════════════════════════════════════════════════════════════
        // BLOG
        // ════════════════════════════════════════════════════════════════════
        async loadBlogPosts() {
            this.blogLoading = true;
            const params = { page: this.blogPage, per_page: 20 };
            if (this.blogStatusFilter) params.status = this.blogStatusFilter;
            if (this.blogSearch) params.search = this.blogSearch;
            const data = await api.get('/api/blog', params);
            this.posts    = data.data ?? [];
            this.blogMeta = data.meta ?? {};
            this.tabs[9].badge = this.blogMeta.drafts || null;
            this.blogLoading = false;
        },
        openPostModal() {
            this.postForm = { title:'', content:'', excerpt:'', status:'draft' };
            this.postCoverFile = null;
            this.postModal = { open: true, editing: false, saving: false };
        },
        openEditPostModal(post) {
            this.postForm = { id: post.id, title: post.title, content: post.content, excerpt: post.excerpt ?? '', status: post.status };
            this.postCoverFile = null;
            this.postModal = { open: true, editing: true, saving: false };
        },
        async savePost() {
            this.postModal.saving = true;
            try {
                const form = new FormData();
                Object.entries(this.postForm).forEach(([k, v]) => { if (v !== null && v !== undefined) form.append(k, v); });
                if (this.postCoverFile) form.append('cover_image', this.postCoverFile);

                const url = this.postModal.editing ? `/api/blog/${this.postForm.id}` : '/api/blog';
                const r   = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: form,
                });
                const res = await r.json();

                if (res.success) {
                    $store.toast.success(res.message ?? 'Article enregistré.');
                    this.postModal.open = false;
                    this.loadBlogPosts();
                } else {
                    const detail = res.errors ? Object.values(res.errors)[0]?.[0] : null;
                    $store.toast.error(detail ?? res.message ?? 'Erreur.');
                }
            } catch (e) {
                $store.toast.error('Erreur réseau, veuillez réessayer.');
            } finally { this.postModal.saving = false; }
        },
        async publishPost(id) {
            const res = await api.post(`/api/blog/${id}/publish`);
            if (res.success) { $store.toast.success(res.message); this.loadBlogPosts(); }
        },
        async archivePost(id) {
            const res = await api.post(`/api/blog/${id}/archive`);
            if (res.success) { $store.toast.success(res.message); this.loadBlogPosts(); }
        },
        async deletePost(id, title) {
            if (!confirm(`Supprimer l'article "${title}" ?`)) return;
            const res = await api.delete(`/api/blog/${id}`);
            if (res.success) { $store.toast.success(res.message); this.loadBlogPosts(); }
        },
    };
}
</script>
@endpush
</x-app-layout>
