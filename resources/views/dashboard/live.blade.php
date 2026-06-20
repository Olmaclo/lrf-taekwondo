<x-app-layout title="Directs / Live">

<div x-data="liveManager()" x-init="load()" class="space-y-6">

    {{-- ── En-tête ──────────────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500"></span>
                Diffusions en direct
            </h1>
            <p class="text-sm text-zinc-400 mt-1">Lance et gère les directs YouTube des événements.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="openMods()"
                    class="inline-flex items-center gap-2 bg-zinc-800 hover:bg-zinc-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition border border-zinc-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Modérateurs
            </button>
            <button @click="showForm = !showForm"
                    class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-black font-bold text-sm px-4 py-2.5 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nouveau direct
            </button>
        </div>
    </div>

    {{-- ── Modérateurs du chat ───────────────────────────────────────────────── --}}
    <div x-show="showMods" x-collapse class="bg-zinc-900/70 border border-zinc-800 rounded-xl p-5 space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-white font-semibold text-sm">Modérateurs du chat</h2>
            <span class="text-xs text-zinc-500">Qui peut supprimer/bannir dans les directs</span>
        </div>
        <div class="space-y-1.5 max-h-80 overflow-y-auto">
            <template x-if="moderators.length === 0">
                <div class="text-sm text-zinc-500 py-3 text-center">Chargement…</div>
            </template>
            <template x-for="u in moderators" :key="u.id">
                <div class="flex items-center justify-between bg-zinc-800/40 rounded-lg px-3 py-2">
                    <div class="min-w-0">
                        <div class="text-sm text-white truncate" x-text="u.name"></div>
                        <div class="text-xs text-zinc-500 truncate" x-text="u.email"></div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <template x-if="u.is_admin">
                            <span class="text-[11px] text-amber-400 border border-amber-500/30 rounded-full px-2 py-0.5">Admin</span>
                        </template>
                        <template x-if="!u.is_admin">
                            <button @click="toggleMod(u)"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg transition"
                                    :class="u.is_moderator ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-zinc-700 text-zinc-300 hover:bg-zinc-600'"
                                    x-text="u.is_moderator ? '✓ Modérateur' : 'Désigner'"></button>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- ── Formulaire de création ───────────────────────────────────────────── --}}
    <div x-show="showForm" x-collapse class="bg-zinc-900/70 border border-zinc-800 rounded-xl p-5 space-y-4">
        <h2 class="text-white font-semibold text-sm">Créer un direct</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-zinc-400 mb-1.5">Événement</label>
                <select x-model="form.event_id" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2.5 text-sm text-white focus:border-amber-500 outline-none">
                    <option value="">Sélectionner…</option>
                    <template x-for="ev in events" :key="ev.id"><option :value="ev.id" x-text="ev.name"></option></template>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-400 mb-1.5">Titre du direct</label>
                <input x-model="form.title" type="text" placeholder="Ex : Finale Senior -68kg"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2.5 text-sm text-white focus:border-amber-500 outline-none">
            </div>
        </div>
        <div>
            <label class="block text-xs font-medium text-zinc-400 mb-1.5">Lien ou ID YouTube</label>
            <input x-model="form.youtube" type="text" placeholder="https://youtube.com/watch?v=… ou https://youtu.be/… ou l'ID"
                   class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2.5 text-sm text-white focus:border-amber-500 outline-none">
            <p class="text-xs text-zinc-500 mt-1.5">Colle l'URL du live YouTube — j'en extrais l'identifiant automatiquement.</p>
        </div>
        <div>
            <label class="block text-xs font-medium text-zinc-400 mb-1.5">Description (optionnel)</label>
            <textarea x-model="form.description" rows="2" placeholder="Quelques mots sur ce direct…"
                      class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2.5 text-sm text-white focus:border-amber-500 outline-none resize-none"></textarea>
        </div>
        <div class="flex items-center gap-2">
            <button @click="create()" :disabled="busy"
                    class="bg-amber-500 hover:bg-amber-400 disabled:opacity-50 text-black font-bold text-sm px-4 py-2.5 rounded-lg transition">
                Créer le direct
            </button>
            <button @click="showForm = false" class="text-zinc-400 hover:text-white text-sm px-3 py-2.5">Annuler</button>
        </div>
    </div>

    {{-- ── Liste des directs ────────────────────────────────────────────────── --}}
    <div class="space-y-3">
        <template x-if="lives.length === 0">
            <div class="text-center py-16 border border-dashed border-zinc-800 rounded-xl">
                <p class="text-zinc-500 text-sm">Aucun direct pour l'instant. Crée le premier ! 🎥</p>
            </div>
        </template>

        <template x-for="live in lives" :key="live.id">
            <div class="bg-zinc-900/70 border border-zinc-800 rounded-xl p-4 flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-4 min-w-0">
                    {{-- Vignette --}}
                    <a :href="live.public_url" target="_blank" class="relative shrink-0 w-28 aspect-video rounded-lg overflow-hidden bg-black border border-zinc-800 group">
                        <img :src="'https://img.youtube.com/vi/' + live.youtube_video_id + '/mqdefault.jpg'" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition" alt="">
                        <span x-show="live.status === 'live'" class="absolute top-1.5 left-1.5 inline-flex items-center gap-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>LIVE
                        </span>
                    </a>
                    <div class="min-w-0">
                        <div class="text-white font-semibold text-sm truncate" x-text="live.title"></div>
                        <div class="text-zinc-500 text-xs mt-0.5 truncate" x-text="live.event ? live.event.name : '—'"></div>
                        <div class="mt-1.5">
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full"
                                  :class="{
                                    'bg-red-500/15 text-red-400 border border-red-500/30': live.status === 'live',
                                    'bg-zinc-700/40 text-zinc-300 border border-zinc-600/40': live.status === 'ended',
                                    'bg-amber-500/10 text-amber-400 border border-amber-500/30': live.status === 'scheduled'
                                  }" x-text="live.status_label"></span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 shrink-0">
                    <button x-show="live.status !== 'live'" @click="start(live.id)"
                            class="inline-flex items-center gap-1.5 bg-red-500 hover:bg-red-400 text-white font-bold text-xs px-3 py-2 rounded-lg transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span> Démarrer
                    </button>
                    <button x-show="live.status === 'live'" @click="stop(live.id)"
                            class="inline-flex items-center gap-1.5 bg-zinc-700 hover:bg-zinc-600 text-white font-semibold text-xs px-3 py-2 rounded-lg transition">
                        ◼ Arrêter
                    </button>
                    <a :href="live.public_url" target="_blank"
                       class="inline-flex items-center gap-1 text-zinc-400 hover:text-white text-xs px-3 py-2 rounded-lg border border-zinc-700 transition">Voir</a>
                    <button @click="remove(live.id)" class="text-zinc-500 hover:text-red-400 p-2 transition" title="Supprimer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

@push('scripts')
<script>
function liveManager() {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    async function req(method, url, body) {
        const res = await fetch(url, {
            method,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: body ? JSON.stringify(body) : undefined,
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(data.message || 'Erreur');
        return data;
    }
    return {
        lives: [],
        events: @json($events),
        showForm: false,
        busy: false,
        form: { event_id: '', title: '', youtube: '', description: '' },

        async load() {
            try { this.lives = (await req('GET', '/api/live')).data ?? []; }
            catch (e) { console.error(e); }
        },
        async create() {
            if (!this.form.event_id || !this.form.title || !this.form.youtube) {
                alert('Événement, titre et lien YouTube sont requis.'); return;
            }
            this.busy = true;
            try {
                await req('POST', '/api/live', this.form);
                this.form = { event_id: '', title: '', youtube: '', description: '' };
                this.showForm = false;
                await this.load();
            } catch (e) { alert(e.message); }
            finally { this.busy = false; }
        },
        async start(id) {
            try { await req('POST', `/api/live/${id}/start`); await this.load(); } catch (e) { alert(e.message); }
        },
        async stop(id) {
            try { await req('POST', `/api/live/${id}/stop`); await this.load(); } catch (e) { alert(e.message); }
        },
        async remove(id) {
            if (!confirm('Supprimer ce direct ?')) return;
            try { await req('DELETE', `/api/live/${id}`); await this.load(); } catch (e) { alert(e.message); }
        },

        showMods: false,
        moderators: [],
        async openMods() {
            this.showMods = !this.showMods;
            if (this.showMods && this.moderators.length === 0) {
                try { this.moderators = (await req('GET', '/api/live/moderators')).data ?? []; }
                catch (e) { alert(e.message); }
            }
        },
        async toggleMod(u) {
            try {
                const res = await req('POST', `/api/live/moderators/${u.id}/toggle`);
                u.is_moderator = res.is_moderator;
            } catch (e) { alert(e.message); }
        },
    };
}
</script>
@endpush

</x-app-layout>
