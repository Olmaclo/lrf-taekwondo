<x-public-layout
    :title="$liveSession->title"
    :description="'Direct — ' . $liveSession->title . ' · ' . ($liveSession->event?->name ?? 'Ligue de Fatick')"
    type="video.other"
>

@php
    $isLive = $liveSession->isLive();
@endphp

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    {{-- ── Top bar : statut + titre ─────────────────────────────────────────── --}}
    <div style="border-bottom: 1px solid rgba(255,255,255,0.07); background: linear-gradient(180deg, rgba(245,158,11,0.04) 0%, transparent 100%);">
        <div style="max-width: 1400px; margin: 0 auto; padding: 1.75rem 2.5rem;">
            <a href="{{ $liveSession->event ? route('public.event-detail', $liveSession->event->slug) : route('public.events') }}"
               style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.3); font-size: 0.72rem; text-decoration: none; margin-bottom: 1.25rem; letter-spacing: 0.06em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif; transition: color 0.2s;"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ $liveSession->event?->name ?? 'Retour' }}
            </a>

            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                @if($isLive)
                <span style="display: inline-flex; align-items: center; gap: 8px; background: #ef4444; color: #fff; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; padding: 6px 14px; border-radius: 6px; font-family: 'Space Grotesk', sans-serif;">
                    <span class="live-dot"></span> EN DIRECT
                </span>
                @else
                <span style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.6); font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding: 6px 14px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.12); font-family: 'Space Grotesk', sans-serif;">
                    ▶ REPLAY
                </span>
                @endif
                <h1 style="font-size: clamp(1.3rem, 3vw, 2rem); font-weight: 700; color: #fff; margin: 0; line-height: 1.2; font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em;">
                    {{ $liveSession->title }}
                </h1>
            </div>
        </div>
    </div>

    {{-- ── Contenu : vidéo + panneau ────────────────────────────────────────── --}}
    <div style="max-width: 1400px; margin: 0 auto; padding: 1.75rem 2.5rem 5rem; display: grid; grid-template-columns: minmax(0, 1fr) 360px; gap: 1.75rem;" id="live-grid">

        {{-- Lecteur --}}
        <div x-data="liveReactions({{ $liveSession->id }}, {{ $isLive ? 'true' : 'false' }})" x-init="init()">
            <div style="position: relative; aspect-ratio: 16/9; background: #0a0a0a; border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; overflow: hidden; box-shadow: 0 24px 64px rgba(0,0,0,0.6);">
                <iframe
                    src="{{ $liveSession->embed_url }}"
                    title="{{ $liveSession->title }}"
                    style="position: absolute; inset: 0; width: 100%; height: 100%; border: 0;"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen></iframe>
                {{-- Couche des réactions flottantes --}}
                <div x-ref="reactionLayer" style="position: absolute; inset: 0; overflow: hidden; pointer-events: none; z-index: 5;"></div>
            </div>

            @if($isLive)
            {{-- Barre de réactions --}}
            <div style="display: flex; align-items: center; gap: 6px; margin-top: 12px; flex-wrap: wrap;">
                <span style="color: rgba(255,255,255,0.3); font-size: 0.72rem; margin-right: 2px;">Réagis :</span>
                <template x-for="e in emojis" :key="e">
                    <button @click="react(e)" x-text="e"
                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 5px 9px; font-size: 1.05rem; cursor: pointer; transition: transform 0.1s, background 0.2s; line-height: 1;"
                            onmouseover="this.style.background='rgba(245,158,11,0.15)'; this.style.transform='scale(1.18)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.transform='scale(1)'"></button>
                </template>
            </div>
            @endif

            {{-- Infos sous la vidéo --}}
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-top: 1.25rem; padding: 1.25rem 0; border-bottom: 1px solid rgba(255,255,255,0.07);">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg style="width: 20px; height: 20px; color: #f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                    </div>
                    <div>
                        <div style="color: #fff; font-weight: 600; font-size: 0.95rem;">{{ $liveSession->event?->name ?? 'Ligue de Fatick' }}</div>
                        @if($isLive)
                        <div style="color: #ef4444; font-size: 0.78rem; font-weight: 600; display: flex; align-items: center; gap: 6px;"><span class="live-dot" style="width:6px;height:6px;"></span> En cours de diffusion</div>
                        @else
                        <div style="color: rgba(255,255,255,0.35); font-size: 0.78rem;">Diffusion terminée @if($liveSession->ended_at)· {{ $liveSession->ended_at->format('d/m/Y') }}@endif</div>
                        @endif
                    </div>
                </div>
                <a href="{{ $liveSession->watch_url }}" target="_blank" rel="noopener"
                   style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); font-size: 0.78rem; font-weight: 600; padding: 9px 16px; border-radius: 8px; text-decoration: none; transition: all 0.2s;"
                   onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.25)'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.borderColor='rgba(255,255,255,0.1)'">
                    <svg style="width: 15px; height: 15px; color: #ff0000;" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    YouTube
                </a>
            </div>

            @if($liveSession->description)
            <p style="color: rgba(255,255,255,0.55); font-size: 0.9rem; line-height: 1.7; margin-top: 1.25rem;">{{ $liveSession->description }}</p>
            @endif
        </div>

        {{-- Panneau latéral : chat en direct --}}
        <aside style="display: flex; flex-direction: column;"
               x-data="liveChat({{ $liveSession->id }}, {{ $isLive ? 'true' : 'false' }}, {{ ($canModerate ?? false) ? 'true' : 'false' }})" x-init="init()">
            <div style="background: #0a0a0a; border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; overflow: hidden; height: 100%; min-height: 480px; max-height: 72vh; display: flex; flex-direction: column;">

                {{-- Header --}}
                <div style="padding: 0.9rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.07); display: flex; align-items: center; justify-content: space-between;">
                    <span style="color: #fff; font-weight: 700; font-size: 0.85rem; font-family: 'Space Grotesk', sans-serif;">💬 Chat en direct</span>
                    <span style="color: rgba(255,255,255,0.3); font-size: 0.7rem;" x-text="messages.length + ' message' + (messages.length > 1 ? 's' : '')"></span>
                </div>

                {{-- Messages --}}
                <div x-ref="box" style="flex: 1; overflow-y: auto; padding: 0.9rem 1rem; display: flex; flex-direction: column; gap: 0.7rem;">
                    <template x-if="messages.length === 0">
                        <div style="color: rgba(255,255,255,0.22); font-size: 0.8rem; text-align: center; margin: auto;">Sois le premier à écrire ! 👋</div>
                    </template>
                    <template x-for="m in messages" :key="m.id">
                        <div class="chat-msg" style="font-size: 0.85rem; line-height: 1.4; position: relative; padding-right: 2px;">
                            <span :style="'color:' + pseudoColor(m.pseudo) + '; font-weight: 700;'" x-text="m.pseudo"></span>
                            <span style="color: rgba(255,255,255,0.2); font-size: 0.62rem; margin-left: 6px;" x-text="m.time"></span>
                            <div style="color: rgba(255,255,255,0.82); word-break: break-word; margin-top: 1px;" x-text="m.message"></div>
                            <template x-if="canModerate">
                                <div class="chat-mod-tools">
                                    <button @click="deleteMessage(m.id)" title="Supprimer ce message"
                                            style="background: rgba(255,255,255,0.08); border: none; color: rgba(255,255,255,0.6); width: 24px; height: 24px; border-radius: 6px; cursor: pointer; display:flex; align-items:center; justify-content:center;">
                                        <svg style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M4 7h16"/></svg>
                                    </button>
                                    <button @click="banMessage(m.id, m.pseudo)" title="Bannir l'auteur"
                                            style="background: rgba(239,68,68,0.15); border: none; color: #f87171; width: 24px; height: 24px; border-radius: 6px; cursor: pointer; display:flex; align-items:center; justify-content:center;">
                                        <svg style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Footer --}}
                <div style="border-top: 1px solid rgba(255,255,255,0.07); padding: 0.85rem 1rem;">
                    <template x-if="!isLive">
                        <div style="color: rgba(255,255,255,0.3); font-size: 0.75rem; text-align: center;">Le chat est fermé — replay</div>
                    </template>

                    <template x-if="isLive && !pseudoSet">
                        <form @submit.prevent="savePseudo()" style="display: flex; gap: 8px;">
                            <input x-model="pseudo" maxlength="40" placeholder="Choisis un pseudo…"
                                   style="flex: 1; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); border-radius: 8px; padding: 9px 12px; color: #fff; font-size: 0.82rem; outline: none;">
                            <button type="submit" style="background: #f59e0b; color: #000; font-weight: 700; font-size: 0.78rem; padding: 9px 16px; border: none; border-radius: 8px; cursor: pointer; white-space: nowrap;">Rejoindre</button>
                        </form>
                    </template>

                    <template x-if="isLive && pseudoSet">
                        <form @submit.prevent="send()" style="display: flex; gap: 8px; align-items: center;">
                            <input x-model="draft" maxlength="500" placeholder="Écris un message…"
                                   style="flex: 1; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); border-radius: 8px; padding: 9px 12px; color: #fff; font-size: 0.85rem; outline: none;"
                                   onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='rgba(255,255,255,0.12)'">
                            <button type="submit" :disabled="sending"
                                    style="background: #f59e0b; color: #000; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border: none; border-radius: 8px; cursor: pointer; flex-shrink: 0;">
                                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.27 3.13a.5.5 0 01.67-.61l16.5 8.5a.5.5 0 010 .9l-16.5 8.5a.5.5 0 01-.67-.61L6 12zm0 0h6"/></svg>
                            </button>
                        </form>
                        <div style="margin-top: 6px; font-size: 0.65rem; color: rgba(255,255,255,0.2);">Tu écris en tant que <span :style="'color:'+pseudoColor(pseudo)+';font-weight:600;'" x-text="pseudo"></span> · <a href="#" @click.prevent="changePseudo()" style="color: rgba(255,255,255,0.35); text-decoration: underline;">changer</a></div>
                    </template>
                </div>
            </div>
        </aside>
    </div>
</div>

@push('head')
<style>
    .live-dot { width: 8px; height: 8px; border-radius: 50%; background: #fff; display: inline-block; box-shadow: 0 0 0 0 rgba(255,255,255,0.7); animation: livePulse 1.6s infinite; }
    @keyframes livePulse {
        0%   { box-shadow: 0 0 0 0 rgba(239,68,68,0.7); transform: scale(1); }
        70%  { box-shadow: 0 0 0 8px rgba(239,68,68,0); transform: scale(1.1); }
        100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); transform: scale(1); }
    }
    @media (max-width: 960px) {
        #live-grid { grid-template-columns: 1fr !important; }
    }
    .chat-mod-tools { position: absolute; top: 0; right: 0; display: none; gap: 4px; background: rgba(10,10,10,0.85); padding: 2px; border-radius: 7px; }
    .chat-msg:hover .chat-mod-tools { display: flex; }
    @keyframes floatReaction {
        0%   { transform: translateY(0) scale(0.5); opacity: 0; }
        12%  { opacity: 1; transform: translateY(-12px) scale(1.15); }
        100% { transform: translateY(-230px) translateX(var(--drift, 0)) scale(0.85); opacity: 0; }
    }
</style>
@endpush

<script>
window.liveChat = function (sessionId, isLive, canModerate) {
    return {
        sessionId, isLive, canModerate,
        messages: [],
        pseudo: localStorage.getItem('live_pseudo') || '',
        pseudoSet: !!localStorage.getItem('live_pseudo'),
        draft: '',
        sending: false,
        seen: new Set(),

        async init() {
            await this.loadHistory();
            this.subscribe();
        },

        async loadHistory() {
            try {
                const res  = await fetch(`/direct/${this.sessionId}/chat`, { headers: { 'Accept': 'application/json' }, cache: 'no-store' });
                const json = await res.json();
                (json.data || []).forEach(m => this.pushMessage(m, false));
                this.scrollDown();
            } catch (e) { /* silencieux */ }
        },

        subscribe() {
            if (window.Echo) {
                const ch = window.Echo.channel('live.' + this.sessionId);
                ch.listen('.chat.message', (m) => this.pushMessage(m));
                ch.listen('.chat.deleted', (e) => this.removeMessages(e.ids || []));
            } else if (this.isLive) {
                // Fallback sans Pusher : rafraîchissement périodique
                setInterval(() => this.loadHistory(), 3500);
            }
        },

        removeMessages(ids) {
            const set = new Set(ids);
            this.messages = this.messages.filter(m => !set.has(m.id));
        },

        modHeaders() {
            return { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content };
        },

        async deleteMessage(id) {
            try {
                await fetch(`/direct/${this.sessionId}/messages/${id}/delete`, { method: 'POST', headers: this.modHeaders() });
                this.removeMessages([id]);
            } catch (e) {}
        },

        async banMessage(id, pseudo) {
            if (!confirm(`Bannir « ${pseudo} » ? Tous ses messages seront supprimés.`)) return;
            try {
                const res  = await fetch(`/direct/${this.sessionId}/messages/${id}/ban`, { method: 'POST', headers: this.modHeaders() });
                const json = await res.json();
                if (!json.success) alert(json.message || 'Action refusée.');
            } catch (e) {}
        },

        pushMessage(m, scroll = true) {
            if (this.seen.has(m.id)) return;
            this.seen.add(m.id);
            this.messages.push(m);
            if (this.messages.length > 250) { this.messages.shift(); }
            if (scroll) this.scrollDown();
        },

        scrollDown() {
            this.$nextTick(() => { const b = this.$refs.box; if (b) b.scrollTop = b.scrollHeight; });
        },

        savePseudo() {
            const p = (this.pseudo || '').trim();
            if (p.length < 2) return;
            localStorage.setItem('live_pseudo', p);
            this.pseudo = p;
            this.pseudoSet = true;
        },

        changePseudo() {
            this.pseudoSet = false;
        },

        async send() {
            const msg = (this.draft || '').trim();
            if (!msg || this.sending) return;
            this.sending = true;
            try {
                const res = await fetch(`/direct/${this.sessionId}/chat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ pseudo: this.pseudo, message: msg }),
                });
                const json = await res.json();
                if (json.success) { this.draft = ''; this.pushMessage(json.data); }
                else { alert(json.message || 'Message refusé.'); }
            } catch (e) { alert('Problème réseau, réessaie.'); }
            finally { this.sending = false; }
        },

        pseudoColor(name) {
            let h = 0;
            for (let i = 0; i < (name || '').length; i++) h = name.charCodeAt(i) + ((h << 5) - h);
            const palette = ['#f59e0b','#ef4444','#3b82f6','#10b981','#a855f7','#ec4899','#06b6d4','#f97316','#84cc16'];
            return palette[Math.abs(h) % palette.length];
        },
    };
};

window.liveReactions = function (sessionId, isLive) {
    return {
        sessionId, isLive,
        emojis: ['❤️','👏','🔥','😮','😂','🥋','💪','🎉'],

        init() {
            if (window.Echo) {
                window.Echo.channel('live.' + this.sessionId)
                    .listen('.reaction', (e) => this.spawn(e.emoji));
            }
        },

        spawn(emoji) {
            const layer = this.$refs.reactionLayer;
            if (!layer) return;
            const el = document.createElement('div');
            el.textContent = emoji;
            const drift = (Math.random() * 70 - 35).toFixed(0);
            el.style.cssText = `position:absolute; bottom:8px; left:${(8 + Math.random()*78).toFixed(1)}%; font-size:${(1.3 + Math.random()*0.9).toFixed(2)}rem; pointer-events:none; --drift:${drift}px; animation: floatReaction 2.6s ease-out forwards; will-change: transform, opacity;`;
            layer.appendChild(el);
            setTimeout(() => el.remove(), 2700);
        },

        async react(emoji) {
            this.spawn(emoji);
            try {
                await fetch(`/direct/${this.sessionId}/reaction`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ emoji }),
                });
            } catch (e) {}
        },
    };
};
</script>

</x-public-layout>
