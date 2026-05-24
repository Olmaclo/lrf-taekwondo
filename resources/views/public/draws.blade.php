<x-public-layout :title="'Tirages — ' . $event->name" :description="'Tirages officiels de ' . $event->name">

<style>
/* ============================================================
   SOTAEMAD Brackets — design classique (adapté depuis v1 WP)
   ============================================================ */

.spb-page {
    --spb-black:        #0a0a0a;
    --spb-dark:         #111111;
    --spb-surface:      #1a1a1a;
    --spb-surface-2:    #222222;
    --spb-border:       #2a2a2a;
    --spb-border-light: #333;
    --spb-gold:         #FFD700;
    --spb-gold-dim:     #E6C200;
    --spb-gold-glow:    rgba(255,215,0,0.12);
    --spb-white:        #fff;
    --spb-gray-200:     #e5e5e5;
    --spb-gray-400:     #a3a3a3;
    --spb-gray-500:     #737373;
    --spb-green:        #22c55e;
    --spb-green-glow:   rgba(34,197,94,0.12);
    --spb-blue:         #3b82f6;
    --spb-red:          #ef4444;
    --spb-radius:       12px;
    --spb-radius-sm:    8px;
    --spb-tr:           0.2s cubic-bezier(0.4,0,0.2,1);
    font-family: 'Space Grotesk', 'Inter', system-ui, sans-serif;
    color: var(--spb-white);
    background: var(--spb-black);
    overflow-x: hidden;
}
.spb-page *, .spb-page *::before, .spb-page *::after { box-sizing: border-box; }

/* ── HERO ── */
.spb-hero { background: var(--spb-surface); border-bottom: 1px solid var(--spb-border); }
.spb-hero__inner { max-width:1280px; margin:0 auto; padding:7rem 2rem 2rem; }
.spb-hero__back {
    display:inline-flex; align-items:center; gap:6px; font-size:13px; color:var(--spb-gray-400);
    text-decoration:none; margin-bottom:16px; transition:color var(--spb-tr);
}
.spb-hero__back:hover { color:var(--spb-gold); }
.spb-hero__title { font-size:clamp(1.4rem,3vw,2rem); font-weight:800; margin:0; color:var(--spb-white); letter-spacing:-0.02em; }
.spb-hero__subtitle { font-size:15px; color:var(--spb-gold); margin:6px 0 20px; font-weight:600; }
.spb-hero__stats { display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap; }
.spb-hero__stat {
    display:flex; flex-direction:column; align-items:center; padding:10px 20px;
    background:var(--spb-surface-2); border:1px solid var(--spb-border); border-radius:var(--spb-radius-sm);
    min-width:80px;
}
.spb-hero__stat-num   { font-size:22px; font-weight:800; color:var(--spb-gold); }
.spb-hero__stat-label { font-size:11px; color:var(--spb-gray-500); text-transform:uppercase; letter-spacing:0.5px; margin-top:2px; }

.spb-btn {
    display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border:none;
    border-radius:var(--spb-radius-sm); font-size:13px; font-weight:600; font-family:inherit;
    cursor:pointer; transition:all var(--spb-tr); text-decoration:none; line-height:1.4;
}
.spb-btn--sm   { padding:8px 16px; }
.spb-btn--ghost { background:transparent; color:var(--spb-gray-400); border:1px solid var(--spb-border); }
.spb-btn--ghost:hover { color:var(--spb-gold); border-color:var(--spb-gold); background:var(--spb-gold-glow); }

/* ── SEARCH ── */
.spb-search { background:var(--spb-black); border-bottom:1px solid var(--spb-border); }
.spb-search__inner { max-width:1280px; margin:0 auto; padding:14px 2rem; }
.spb-search__box {
    display:flex; align-items:center; gap:10px; padding:10px 16px;
    background:var(--spb-surface); border:2px solid var(--spb-border); border-radius:var(--spb-radius);
    transition:border-color var(--spb-tr);
}
.spb-search__box:focus-within { border-color:var(--spb-gold); box-shadow:0 0 0 3px var(--spb-gold-glow); }
.spb-search__icon { color:var(--spb-gray-500); flex-shrink:0; }
.spb-search__box:focus-within .spb-search__icon { color:var(--spb-gold); }
.spb-search__input {
    flex:1; border:none; background:transparent; outline:none;
    color:var(--spb-white); font-size:14px; font-family:inherit;
}
.spb-search__input::placeholder { color:var(--spb-gray-500); }
.spb-search__clear {
    display:flex; align-items:center; justify-content:center; width:26px; height:26px;
    background:var(--spb-surface-2); border:1px solid var(--spb-border); border-radius:50%;
    color:var(--spb-gray-400); cursor:pointer; transition:all var(--spb-tr); flex-shrink:0;
}
.spb-search__clear:hover { background:var(--spb-red); color:#fff; border-color:var(--spb-red); }
.spb-search__results {
    display:flex; align-items:center; margin-top:8px; padding:7px 12px;
    background:var(--spb-surface); border-radius:var(--spb-radius-sm); font-size:13px; color:var(--spb-gray-400);
}

/* ── QUICK NAV ── */
.spb-nav { background:var(--spb-surface); border-bottom:1px solid var(--spb-border); overflow-x:auto; }
.spb-nav__inner { max-width:1280px; margin:0 auto; padding:10px 2rem; display:flex; gap:8px; flex-wrap:nowrap; }
.spb-nav__item {
    display:flex; align-items:center; gap:7px; padding:7px 13px;
    background:var(--spb-surface-2); border:1px solid var(--spb-border); border-radius:var(--spb-radius-sm);
    text-decoration:none; color:var(--spb-gray-200); font-size:13px; white-space:nowrap; transition:all var(--spb-tr); flex-shrink:0;
}
.spb-nav__item:hover { border-color:var(--spb-gold); color:var(--spb-white); }
.spb-nav__item-label  { font-weight:600; }
.spb-nav__item-count  { font-size:11px; color:var(--spb-gray-500); }
.spb-nav__item-dot    { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
.spb-nav__item-dot--done { background:var(--spb-green); }
.spb-nav__item-dot--wait { background:var(--spb-gray-500); }

/* ── CONTENT ── */
.spb-content { max-width:1280px; margin:0 auto; padding:24px 2rem 60px; }

/* ── SECTION ── */
.spb-section { margin-bottom:48px; scroll-margin-top:80px; }
.spb-section__header { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:16px; padding-bottom:12px; border-bottom:1px solid var(--spb-border); flex-wrap:wrap; }
.spb-section__title-group { display:flex; align-items:baseline; gap:12px; flex-wrap:wrap; }
.spb-section__title   { font-size:18px; font-weight:700; margin:0; color:var(--spb-white); }
.spb-section__count   { font-size:13px; color:var(--spb-gray-500); }

.spb-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }
.spb-badge--done { background:var(--spb-green-glow); color:var(--spb-green); }
.spb-badge--wait { background:rgba(115,115,115,0.12); color:var(--spb-gray-500); }

.spb-genre-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:12px; font-size:11px; font-weight:600; margin-left:10px; vertical-align:middle; }
.spb-genre-badge--male   { background:rgba(59,130,246,0.15); color:#60A5FA; border:1px solid rgba(59,130,246,0.25); }
.spb-genre-badge--female { background:rgba(236,72,153,0.15); color:#F472B6; border:1px solid rgba(236,72,153,0.25); }

/* ── BRACKET (elimination directe) ── */
.spb-bracket { overflow-x:auto; overflow-y:visible; padding:16px 0; -webkit-overflow-scrolling:touch; }
.spb-bracket::-webkit-scrollbar { height:8px; }
.spb-bracket::-webkit-scrollbar-track { background:var(--spb-surface); border-radius:4px; }
.spb-bracket::-webkit-scrollbar-thumb { background:rgba(255,215,0,0.3); border-radius:4px; }
.spb-bracket::-webkit-scrollbar-thumb:hover { background:rgba(255,215,0,0.5); }

.spb-bracket__wrapper { display:flex; align-items:stretch; min-width:max-content; gap:0; }
.spb-bracket__round { display:flex; flex-direction:column; min-width:260px; padding:0 16px; }
.spb-bracket__round--champion { min-width:180px; }
.spb-bracket__round-title {
    text-align:center; color:var(--spb-gold); font-weight:700; font-size:12px; padding:10px 14px; margin-bottom:16px;
    background:var(--spb-gold-glow); border:1px solid rgba(255,215,0,0.2); border-radius:var(--spb-radius-sm);
    text-transform:uppercase; letter-spacing:1px;
}
.spb-bracket__round-title--final    { background:rgba(255,215,0,0.2); border-color:var(--spb-gold); }
.spb-bracket__round-title--champion { background:linear-gradient(135deg,rgba(255,215,0,0.25),rgba(230,194,0,0.15)); border-color:var(--spb-gold); }

.spb-bracket__matches { display:flex; flex-direction:column; justify-content:space-around; flex:1; gap:0; }

/* match card */
.spb-match {
    position:relative; margin:6px 0;
    background:var(--spb-surface); border:1px solid var(--spb-border); border-radius:var(--spb-radius-sm);
    overflow:visible; transition:all var(--spb-tr);
}
.spb-match:hover  { border-color:var(--spb-gold); box-shadow:0 2px 16px var(--spb-gold-glow); }
.spb-match--final { border:2px solid var(--spb-gold); box-shadow:0 0 24px var(--spb-gold-glow); }
.spb-match--bye   { border-color:rgba(34,197,94,0.3); }

.spb-match__num {
    position:absolute; top:-10px; left:50%; transform:translateX(-50%); z-index:2;
    background:var(--spb-gold); color:var(--spb-black); font-size:10px; font-weight:800;
    padding:2px 10px; border-radius:10px; white-space:nowrap; box-shadow:0 2px 6px rgba(255,215,0,0.3);
}
.spb-match__num--final { font-size:12px; padding:4px 14px; background:linear-gradient(135deg,#FFD700,#FFA500); }

.spb-match__players { padding:4px 0; }
.spb-match__player {
    display:flex; align-items:center; gap:10px; padding:10px 14px;
    border-bottom:1px solid var(--spb-border); transition:background var(--spb-tr);
}
.spb-match__player:last-child { border-bottom:none; }
.spb-match__player:hover { background:rgba(255,215,0,0.04); }
.spb-match__player--winner { background:rgba(34,197,94,0.08) !important; }
.spb-match__player--winner .spb-match__name { color:var(--spb-green); font-weight:700; }
.spb-match__player--bye { background:var(--spb-green-glow); }
.spb-match__player--tbd { opacity:0.5; }

.spb-match__seed {
    width:24px; height:24px; display:flex; align-items:center; justify-content:center;
    background:var(--spb-surface-2); border:1px solid var(--spb-border-light); border-radius:50%;
    font-size:10px; font-weight:700; color:var(--spb-gold); flex-shrink:0;
}
.spb-match__seed--bye { background:var(--spb-green); border-color:var(--spb-green); color:#fff; }
.spb-match__seed--tbd { background:var(--spb-surface-2); color:var(--spb-gray-500); border-color:var(--spb-border); }

.spb-match__info   { min-width:0; }
.spb-match__name   { display:block; font-size:13px; font-weight:600; color:var(--spb-white); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.spb-match__name--bye { color:var(--spb-green); }
.spb-match__name--tbd { color:var(--spb-gray-500); font-style:italic; font-weight:400; }
.spb-match__club   { display:block; font-size:10px; color:var(--spb-gray-500); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

/* matchup = groupe de 2 matchs qui se connectent au round suivant */
.spb-matchup { display:flex; flex-direction:column; justify-content:center; flex:1; position:relative; }

/* ligne horizontale droite de chaque match vers la ligne verticale */
.spb-match__connector {
    position:absolute; right:-16px; top:50%; transform:translateY(-1px);
    width:16px; height:2px; background:var(--spb-border-light); z-index:1;
}
/* ligne verticale reliant les 2 matchs */
.spb-matchup::after {
    content:""; position:absolute; right:-16px; top:25%; bottom:25%;
    width:2px; background:var(--spb-border-light); z-index:1;
}
/* ligne horizontale de sortie vers le round suivant */
.spb-matchup::before {
    content:""; position:absolute; right:-32px; top:50%; transform:translateY(-1px);
    width:16px; height:2px; background:var(--spb-border-light); z-index:1;
}

/* champion */
.spb-champion {
    display:flex; flex-direction:column; align-items:center; padding:24px 16px;
    background:var(--spb-surface); border:2px solid var(--spb-gold); border-radius:var(--spb-radius);
    text-align:center; box-shadow:0 0 30px var(--spb-gold-glow);
}
.spb-champion__icon  { color:var(--spb-gold); margin-bottom:8px; }
.spb-champion__title { font-size:13px; font-weight:800; color:var(--spb-gold); text-transform:uppercase; letter-spacing:1.5px; }
.spb-champion__name  { font-size:13px; color:var(--spb-gray-400); margin-top:4px; }

/* légende */
.spb-legend { display:flex; gap:20px; padding:14px 16px; background:var(--spb-surface); border-radius:var(--spb-radius-sm); margin-top:12px; flex-wrap:wrap; }
.spb-legend__item { display:flex; align-items:center; gap:7px; font-size:12px; color:var(--spb-gray-400); }
.spb-legend__dot  { width:12px; height:12px; border-radius:3px; }
.spb-legend__dot--gold  { background:var(--spb-gold); }
.spb-legend__dot--green { background:var(--spb-green); }
.spb-legend__dot--gray  { background:var(--spb-gray-500); }

/* ── POOLS ── */
.spb-pools { margin-top:8px; }
.spb-pools__header { display:flex; align-items:center; gap:10px; font-size:16px; font-weight:700; color:var(--spb-white); margin:0 0 6px; }
.spb-pools__header svg { color:var(--spb-gold); }
.spb-pools__desc { font-size:13px; color:var(--spb-gray-500); margin:0 0 20px; }
.spb-pools__grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(340px,1fr)); gap:20px; }

.spb-pool { background:var(--spb-surface); border:1px solid var(--spb-border); border-radius:var(--spb-radius); overflow:hidden; }
.spb-pool__header {
    display:flex; align-items:center; justify-content:space-between; padding:12px 16px;
    background:var(--spb-gold-glow); border-bottom:1px solid var(--spb-border);
}
.spb-pool__letter { font-size:15px; font-weight:800; color:var(--spb-gold); }
.spb-pool__count  { font-size:12px; color:var(--spb-gray-500); }

.spb-pool__table { padding:0; }
.spb-pool__row {
    display:grid; grid-template-columns:28px 1fr 36px 36px 36px; align-items:center; gap:4px;
    padding:9px 14px; border-bottom:1px solid var(--spb-border);
}
.spb-pool__row:last-child { border-bottom:none; }
.spb-pool__row--head { background:var(--spb-surface-2); }
.spb-pool__row--head .spb-pool__cell { font-size:10px; font-weight:700; color:var(--spb-gray-500); text-transform:uppercase; }
.spb-pool__row--qualified { background:var(--spb-green-glow); }
.spb-pool__row--qualified .spb-pool__cell--rank { color:var(--spb-green); font-weight:800; }

.spb-pool__cell         { font-size:13px; color:var(--spb-gray-200); }
.spb-pool__cell--rank   { font-weight:700; color:var(--spb-gold); text-align:center; }
.spb-pool__cell--name   { min-width:0; overflow:hidden; }
.spb-pool__cell--stat   { text-align:center; color:var(--spb-gray-500); font-size:12px; }

.spb-pool__athlete-name { display:block; font-weight:600; font-size:13px; color:var(--spb-white); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.spb-pool__athlete-club { display:block; font-size:10px; color:var(--spb-gray-500); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

.spb-pool__matches       { padding:10px 14px; border-top:1px solid var(--spb-border); }
.spb-pool__matches-title { font-size:11px; font-weight:700; color:var(--spb-gray-400); text-transform:uppercase; margin:0 0 8px; letter-spacing:0.5px; }

.spb-pool__match { display:flex; align-items:center; gap:8px; padding:5px 0; font-size:12px; border-bottom:1px solid rgba(42,42,42,0.5); }
.spb-pool__match:last-child { border-bottom:none; }
.spb-pool__match--done .spb-pool__match-a { color:var(--spb-gray-400); }
.spb-pool__match-num { width:20px; height:20px; display:flex; align-items:center; justify-content:center; background:var(--spb-surface-2); border-radius:50%; font-size:10px; font-weight:700; color:var(--spb-gold); flex-shrink:0; }
.spb-pool__match-a  { color:var(--spb-gray-200); font-weight:500; flex:1; min-width:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.spb-pool__match-a--winner { color:var(--spb-green); font-weight:700; }
.spb-pool__match-vs { color:var(--spb-gold); font-weight:800; font-size:10px; text-transform:uppercase; flex-shrink:0; }

/* Finals (pool system) */
.spb-finals { margin-top:28px; padding-top:24px; border-top:1px solid var(--spb-border); }
.spb-finals__title { font-size:15px; font-weight:700; color:var(--spb-white); margin:0 0 16px; display:flex; align-items:center; gap:8px; }
.spb-finals__title span { color:var(--spb-gold); }
.spb-finals__grid  { display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:12px; }
.spb-finals__match {
    background:var(--spb-surface); border:1px solid var(--spb-border); border-radius:var(--spb-radius-sm); overflow:hidden;
}
.spb-finals__match--finale { border-color:var(--spb-gold); box-shadow:0 0 20px var(--spb-gold-glow); }
.spb-finals__match-label {
    padding:6px 14px; background:var(--spb-gold-glow); border-bottom:1px solid var(--spb-border);
    font-size:10px; font-weight:800; color:var(--spb-gold); text-transform:uppercase; letter-spacing:1px;
}
.spb-finals__player { display:flex; align-items:center; gap:10px; padding:10px 14px; border-bottom:1px solid var(--spb-border); }
.spb-finals__player:last-child { border-bottom:none; }
.spb-finals__player--winner { background:rgba(34,197,94,0.08); }
.spb-finals__player--winner .spb-finals__name { color:var(--spb-green); font-weight:700; }
.spb-finals__player--placeholder { opacity:0.5; }
.spb-finals__seed { width:22px; height:22px; display:flex; align-items:center; justify-content:center; background:var(--spb-surface-2); border:1px solid var(--spb-border-light); border-radius:50%; font-size:10px; font-weight:700; color:var(--spb-gold); flex-shrink:0; }
.spb-finals__name { font-size:13px; font-weight:600; color:var(--spb-white); }
.spb-finals__club { font-size:10px; color:var(--spb-gray-500); }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .spb-hero__inner { padding-top:6rem; }
    .spb-content     { padding:16px 1rem 40px; }
    .spb-bracket     { margin:0 -1rem; padding:16px 1rem; }
    .spb-pools__grid { grid-template-columns:1fr; }
    .spb-pool__row   { grid-template-columns:24px 1fr 30px 30px 30px; gap:2px; padding:7px 10px; }
}
@media (max-width:1024px) {
    .spb-bracket::after {
        content:"← Glissez pour parcourir →"; display:block; text-align:center;
        font-size:11px; color:var(--spb-gray-500); padding:8px 0 0; opacity:0.7;
    }
}
@media print {
    .spb-search, .spb-nav, .spb-hero .spb-btn { display:none !important; }
    .spb-page { background:#fff; color:#000; }
    .spb-match { border:1px solid #ccc; }
    .spb-match__name { color:#000; }
    .spb-champion { border:2px solid #333; box-shadow:none; }
}
</style>

<div class="spb-page">

{{-- ── HERO ─────────────────────────────────────────────────────────── --}}
<section class="spb-hero">
    <div class="spb-hero__inner">
        <a href="{{ route('public.event-detail', $event->slug) }}" class="spb-hero__back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Retour à l'événement
        </a>
        <h1 class="spb-hero__title">{{ $event->name }}</h1>
        <p class="spb-hero__subtitle">Tableaux de compétition officiels</p>
        <div class="spb-hero__stats">
            <div class="spb-hero__stat">
                <span class="spb-hero__stat-num">{{ $draws->sum('total_athletes') }}</span>
                <span class="spb-hero__stat-label">Athlètes</span>
            </div>
            <div class="spb-hero__stat">
                <span class="spb-hero__stat-num">{{ $draws->count() }}</span>
                <span class="spb-hero__stat-label">Catégories</span>
            </div>
            <div class="spb-hero__stat">
                <span class="spb-hero__stat-num">{{ $draws->where('use_pools', false)->count() }}</span>
                <span class="spb-hero__stat-label">Brackets</span>
            </div>
            <div class="spb-hero__stat">
                <span class="spb-hero__stat-num">{{ $draws->where('use_pools', true)->count() }}</span>
                <span class="spb-hero__stat-label">Poules</span>
            </div>
        </div>
        <button class="spb-btn spb-btn--ghost spb-btn--sm" onclick="window.print()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Imprimer
        </button>
    </div>
</section>

{{-- ── BARRE DE RECHERCHE ──────────────────────────────────────────────── --}}
<div class="spb-search">
    <div class="spb-search__inner">
        <div class="spb-search__box">
            <svg class="spb-search__icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="spb-search" class="spb-search__input" placeholder="Rechercher une catégorie, un athlète, un club…" autocomplete="off">
            <button id="spb-search-clear" class="spb-search__clear" style="display:none;" onclick="spbClear()">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div id="spb-search-results" class="spb-search__results" style="display:none;"></div>
    </div>
</div>

{{-- ── NAVIGATION RAPIDE ───────────────────────────────────────────────── --}}
@if($draws->isNotEmpty())
<nav class="spb-nav">
    <div class="spb-nav__inner">
        @foreach($draws as $draw)
        <a href="#cat-{{ $draw->id }}" class="spb-nav__item">
            <span class="spb-nav__item-label">{{ $draw->age_category }} {{ $draw->weight_category }}</span>
            <span class="spb-nav__item-count">{{ $draw->total_athletes }}</span>
            <span class="spb-nav__item-dot spb-nav__item-dot--done"></span>
        </a>
        @endforeach
    </div>
</nav>
@endif

{{-- ── CONTENU PRINCIPAL ───────────────────────────────────────────────── --}}
<div class="spb-content">
    @if($draws->isEmpty())
        <div style="text-align:center;padding:5rem 0;color:var(--spb-gray-500);">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 16px;display:block;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p>Les tirages n'ont pas encore été effectués.</p>
        </div>
    @else
    @foreach($draws as $draw)
    @php
        $genderBadge = $draw->gender === 'M'
            ? '<span class="spb-genre-badge spb-genre-badge--male">♂ Masculin</span>'
            : '<span class="spb-genre-badge spb-genre-badge--female">♀ Féminin</span>';

        // Champion (pour élimination directe)
        $champion = null;
        if (!$draw->use_pools && $draw->matches) {
            $finalMatch = collect($draw->matches)->where('round', 1)->first();
            $champion = $finalMatch['winner']['name'] ?? null;
        }
    @endphp
    <section class="spb-section" id="cat-{{ $draw->id }}">

        {{-- En-tête de section --}}
        <div class="spb-section__header">
            <div class="spb-section__title-group">
                <h2 class="spb-section__title">
                    {{ $draw->age_category }} {{ $draw->weight_category }}
                    {!! $genderBadge !!}
                </h2>
                <span class="spb-section__count">{{ $draw->total_athletes }} athlètes</span>
            </div>
            <span class="spb-badge spb-badge--done">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Tirage effectué
            </span>
        </div>

        {{-- ── BRACKET ÉLIMINATION DIRECTE ── --}}
        @if(!$draw->use_pools && $draw->matches)
        @php
            $matchesByRound = collect($draw->matches)->groupBy('round')->sortKeysDesc();
            $maxRound       = $matchesByRound->keys()->first();
            $roundNames     = [
                1 => 'Finale',
                2 => 'Demi-finales',
                3 => 'Quarts de finale',
                4 => 'Huitièmes de finale',
                5 => 'Seizièmes de finale',
            ];
        @endphp
        <div class="spb-bracket">
            <div class="spb-bracket__wrapper">

                @foreach($matchesByRound as $round => $roundMatches)
                @php
                    $isFinal  = ($round == 1);
                    $hasNext  = ($round > 1);
                    $rName    = $roundNames[$round] ?? 'Tour ' . ($maxRound - $round + 1);
                    $sorted   = $roundMatches->sortBy('position')->values();
                @endphp
                <div class="spb-bracket__round" data-round="{{ $round }}">
                    <div class="spb-bracket__round-title {{ $isFinal ? 'spb-bracket__round-title--final' : '' }}">
                        {{ $rName }}
                    </div>
                    <div class="spb-bracket__matches">
                        @for($idx = 0; $idx < $sorted->count(); $idx += 2)
                        @php
                            $m1         = $sorted[$idx];
                            $m2         = $sorted[$idx + 1] ?? null;
                            $isMatchup  = $hasNext && $m2;
                            $isBye1     = $m1['is_bye'] ?? false;
                            $isBye2     = $m2 ? ($m2['is_bye'] ?? false) : false;
                            $winner1Id  = $m1['winner_id'] ?? null;
                            $winner2Id  = $m2['winner_id'] ?? null;
                            $pos1       = $m1['position'] ?? ($idx + 1);
                            $pos2       = $m2['position'] ?? ($idx + 2);
                            $seed1a     = ($pos1 - 1) * 2 + 1;
                            $seed1b     = ($pos1 - 1) * 2 + 2;
                            $seed2a     = ($pos2 - 1) * 2 + 1;
                            $seed2b     = ($pos2 - 1) * 2 + 2;
                        @endphp

                        @if($isMatchup)<div class="spb-matchup">@endif

                        {{-- Match 1 --}}
                        <div class="spb-match {{ $isBye1 ? 'spb-match--bye' : '' }} {{ $isFinal && !$m2 ? 'spb-match--final' : '' }}"
                             data-match="{{ $m1['id'] ?? $idx+1 }}">
                            <span class="spb-match__num {{ $isFinal && !$m2 ? 'spb-match__num--final' : '' }}">
                                {{ $isFinal && !$m2 ? 'FINALE' : 'M' . ($m1['id'] ?? $idx+1) }}
                            </span>
                            <div class="spb-match__players">
                                {{-- Athlète 1 --}}
                                @if($m1['athlete1'] ?? null)
                                <div class="spb-match__player {{ ($winner1Id && $winner1Id == ($m1['athlete1']['id'] ?? null)) ? 'spb-match__player--winner' : '' }}">
                                    <span class="spb-match__seed">{{ $seed1a }}</span>
                                    <div class="spb-match__info">
                                        <span class="spb-match__name">{{ $m1['athlete1']['name'] ?? '?' }}</span>
                                        <span class="spb-match__club">{{ $m1['athlete1']['club'] ?? '' }}</span>
                                    </div>
                                </div>
                                @else
                                <div class="spb-match__player spb-match__player--tbd">
                                    <span class="spb-match__seed spb-match__seed--tbd">?</span>
                                    <div class="spb-match__info"><span class="spb-match__name spb-match__name--tbd">En attente</span></div>
                                </div>
                                @endif

                                {{-- Athlète 2 / BYE --}}
                                @if($m1['athlete2'] ?? null)
                                <div class="spb-match__player {{ ($winner1Id && $winner1Id == ($m1['athlete2']['id'] ?? null)) ? 'spb-match__player--winner' : '' }}">
                                    <span class="spb-match__seed">{{ $seed1b }}</span>
                                    <div class="spb-match__info">
                                        <span class="spb-match__name">{{ $m1['athlete2']['name'] ?? '?' }}</span>
                                        <span class="spb-match__club">{{ $m1['athlete2']['club'] ?? '' }}</span>
                                    </div>
                                </div>
                                @elseif($isBye1)
                                <div class="spb-match__player spb-match__player--bye">
                                    <span class="spb-match__seed spb-match__seed--bye">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    </span>
                                    <div class="spb-match__info">
                                        <span class="spb-match__name spb-match__name--bye">Exempt (Bye)</span>
                                        <span class="spb-match__club">Qualifié directement</span>
                                    </div>
                                </div>
                                @else
                                <div class="spb-match__player spb-match__player--tbd">
                                    <span class="spb-match__seed spb-match__seed--tbd">?</span>
                                    <div class="spb-match__info"><span class="spb-match__name spb-match__name--tbd">En attente</span></div>
                                </div>
                                @endif
                            </div>
                            @if($hasNext)<div class="spb-match__connector"></div>@endif
                        </div>

                        {{-- Match 2 (si existe dans le round) --}}
                        @if($m2)
                        <div class="spb-match {{ $isBye2 ? 'spb-match--bye' : '' }}"
                             data-match="{{ $m2['id'] ?? $idx+2 }}">
                            <span class="spb-match__num">M{{ $m2['id'] ?? $idx+2 }}</span>
                            <div class="spb-match__players">
                                @if($m2['athlete1'] ?? null)
                                <div class="spb-match__player {{ ($winner2Id && $winner2Id == ($m2['athlete1']['id'] ?? null)) ? 'spb-match__player--winner' : '' }}">
                                    <span class="spb-match__seed">{{ $seed2a }}</span>
                                    <div class="spb-match__info">
                                        <span class="spb-match__name">{{ $m2['athlete1']['name'] ?? '?' }}</span>
                                        <span class="spb-match__club">{{ $m2['athlete1']['club'] ?? '' }}</span>
                                    </div>
                                </div>
                                @else
                                <div class="spb-match__player spb-match__player--tbd">
                                    <span class="spb-match__seed spb-match__seed--tbd">?</span>
                                    <div class="spb-match__info"><span class="spb-match__name spb-match__name--tbd">En attente</span></div>
                                </div>
                                @endif

                                @if($m2['athlete2'] ?? null)
                                <div class="spb-match__player {{ ($winner2Id && $winner2Id == ($m2['athlete2']['id'] ?? null)) ? 'spb-match__player--winner' : '' }}">
                                    <span class="spb-match__seed">{{ $seed2b }}</span>
                                    <div class="spb-match__info">
                                        <span class="spb-match__name">{{ $m2['athlete2']['name'] ?? '?' }}</span>
                                        <span class="spb-match__club">{{ $m2['athlete2']['club'] ?? '' }}</span>
                                    </div>
                                </div>
                                @elseif($isBye2)
                                <div class="spb-match__player spb-match__player--bye">
                                    <span class="spb-match__seed spb-match__seed--bye">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    </span>
                                    <div class="spb-match__info">
                                        <span class="spb-match__name spb-match__name--bye">Exempt (Bye)</span>
                                        <span class="spb-match__club">Qualifié directement</span>
                                    </div>
                                </div>
                                @else
                                <div class="spb-match__player spb-match__player--tbd">
                                    <span class="spb-match__seed spb-match__seed--tbd">?</span>
                                    <div class="spb-match__info"><span class="spb-match__name spb-match__name--tbd">En attente</span></div>
                                </div>
                                @endif
                            </div>
                            @if($hasNext)<div class="spb-match__connector"></div>@endif
                        </div>
                        @endif

                        @if($isMatchup)</div>@endif
                        @endfor
                    </div>
                </div>
                @endforeach

                {{-- CHAMPION --}}
                <div class="spb-bracket__round spb-bracket__round--champion" data-round="champion">
                    <div class="spb-bracket__round-title spb-bracket__round-title--champion">Champion</div>
                    <div class="spb-bracket__matches">
                        <div class="spb-champion">
                            <svg class="spb-champion__icon" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9H4.5a2.5 2.5 0 010-5H6"/><path d="M18 9h1.5a2.5 2.5 0 000-5H18"/>
                                <path d="M4 22h16"/><path d="M10 22V8a6 6 0 0112 0v14"/><path d="M14 22V8a6 6 0 00-12 0v14"/>
                            </svg>
                            <span class="spb-champion__title">Vainqueur</span>
                            <span class="spb-champion__name">{{ $champion ?? 'En attente' }}</span>
                        </div>
                    </div>
                </div>

            </div>{{-- end wrapper --}}

            {{-- Légende --}}
            <div class="spb-legend">
                <div class="spb-legend__item"><span class="spb-legend__dot spb-legend__dot--gold"></span> Match à jouer</div>
                <div class="spb-legend__item"><span class="spb-legend__dot spb-legend__dot--green"></span> Vainqueur / Exempt</div>
                <div class="spb-legend__item"><span class="spb-legend__dot spb-legend__dot--gray"></span> En attente</div>
            </div>
        </div>{{-- end bracket --}}

        {{-- ── POULES ── --}}
        @elseif($draw->use_pools && $draw->pools)
        @php
            $pools   = $draw->pools['pools']   ?? [];
            $finals  = $draw->pools['finals']  ?? [];
        @endphp
        <div class="spb-pools">
            <div class="spb-pools__header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12l2 2 4-4"/></svg>
                Phase de poules — {{ count($pools) }} poule(s)
            </div>
            <p class="spb-pools__desc">Les vainqueurs de chaque poule se qualifient pour la phase finale.</p>

            <div class="spb-pools__grid">
                @foreach($pools as $pool)
                @php
                    // Calculer les victoires par athlète
                    $wins = [];
                    foreach ($pool['athletes'] as $a) { $wins[$a['id']] = 0; }
                    foreach ($pool['matches'] as $m) {
                        if (!empty($m['winner_id'])) {
                            $wins[$m['winner_id']] = ($wins[$m['winner_id']] ?? 0) + 1;
                        }
                    }
                    // Classer par victoires décroissantes
                    $ranked = $pool['athletes'];
                    usort($ranked, fn($a, $b) => ($wins[$b['id']] ?? 0) <=> ($wins[$a['id']] ?? 0));
                    $poolWinnerId = $pool['winner']['id'] ?? ($ranked[0]['id'] ?? null);
                @endphp
                <div class="spb-pool">
                    <div class="spb-pool__header">
                        <span class="spb-pool__letter">{{ $pool['name'] }}</span>
                        <span class="spb-pool__count">{{ count($pool['athletes']) }} athlètes</span>
                    </div>

                    {{-- Classement --}}
                    <div class="spb-pool__table">
                        <div class="spb-pool__row spb-pool__row--head">
                            <span class="spb-pool__cell spb-pool__cell--rank">#</span>
                            <span class="spb-pool__cell spb-pool__cell--name">Athlète</span>
                            <span class="spb-pool__cell spb-pool__cell--stat">V</span>
                            <span class="spb-pool__cell spb-pool__cell--stat">D</span>
                            <span class="spb-pool__cell spb-pool__cell--stat">Pts</span>
                        </div>
                        @foreach($ranked as $rIdx => $athlete)
                        @php
                            $w = $wins[$athlete['id']] ?? 0;
                            $totalGames = count($pool['athletes']) - 1;
                            $d = $totalGames - $w;
                            $isQualified = ($rIdx === 0);
                        @endphp
                        <div class="spb-pool__row {{ $isQualified ? 'spb-pool__row--qualified' : '' }}">
                            <span class="spb-pool__cell spb-pool__cell--rank">{{ $rIdx + 1 }}</span>
                            <span class="spb-pool__cell spb-pool__cell--name">
                                <span class="spb-pool__athlete-name">{{ $athlete['name'] }}</span>
                                <span class="spb-pool__athlete-club">{{ $athlete['club'] }}</span>
                            </span>
                            <span class="spb-pool__cell spb-pool__cell--stat">{{ $w }}</span>
                            <span class="spb-pool__cell spb-pool__cell--stat">{{ $d < 0 ? '—' : $d }}</span>
                            <span class="spb-pool__cell spb-pool__cell--stat">{{ $w }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Matchs de poule --}}
                    <div class="spb-pool__matches">
                        <h4 class="spb-pool__matches-title">Matchs</h4>
                        @foreach($pool['matches'] as $pm)
                        @php
                            $hasPmWinner = !empty($pm['winner_id']);
                            $a1w = $hasPmWinner && $pm['winner_id'] == ($pm['athlete1']['id'] ?? null);
                            $a2w = $hasPmWinner && $pm['winner_id'] == ($pm['athlete2']['id'] ?? null);
                        @endphp
                        <div class="spb-pool__match {{ $hasPmWinner ? 'spb-pool__match--done' : '' }}">
                            <span class="spb-pool__match-num">{{ $pm['position'] ?? loop->index + 1 }}</span>
                            <span class="spb-pool__match-a {{ $a1w ? 'spb-pool__match-a--winner' : '' }}">
                                {{ $pm['athlete1']['name'] ?? '?' }}
                            </span>
                            <span class="spb-pool__match-vs">vs</span>
                            <span class="spb-pool__match-a {{ $a2w ? 'spb-pool__match-a--winner' : '' }}">
                                {{ $pm['athlete2']['name'] ?? '?' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>{{-- end pools grid --}}

            {{-- Phase finale --}}
            @if(!empty($finals))
            <div class="spb-finals">
                <h3 class="spb-finals__title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13"/><path d="M8 12h13"/><path d="M8 18h13"/><path d="M3 6h.01"/><path d="M3 12h.01"/><path d="M3 18h.01"/></svg>
                    Phase <span>finale</span>
                </h3>
                <div class="spb-finals__grid">
                    @foreach($finals as $fm)
                    @php
                        $isFinalMatch = str_contains(strtoupper($fm['pool'] ?? ''), 'FINALE') && !str_contains(strtoupper($fm['pool'] ?? ''), 'DEMI');
                        $fWinnerId    = $fm['winner_id'] ?? null;
                        $fa1          = $fm['athlete1'] ?? null;
                        $fa2          = $fm['athlete2'] ?? null;
                        $fa1IsPlaceholder = $fa1['placeholder'] ?? false;
                        $fa2IsPlaceholder = $fa2['placeholder'] ?? false;
                    @endphp
                    <div class="spb-finals__match {{ $isFinalMatch ? 'spb-finals__match--finale' : '' }}">
                        <div class="spb-finals__match-label">{{ $fm['pool'] ?? 'Match' }}</div>
                        <div class="spb-finals__player {{ ($fWinnerId && !$fa1IsPlaceholder && $fWinnerId == ($fa1['id'] ?? null)) ? 'spb-finals__player--winner' : '' }} {{ $fa1IsPlaceholder ? 'spb-finals__player--placeholder' : '' }}">
                            <span class="spb-finals__seed">1</span>
                            <div>
                                <div class="spb-finals__name">{{ $fa1['name'] ?? 'En attente' }}</div>
                                @if(!$fa1IsPlaceholder && !empty($fa1['club']))<div class="spb-finals__club">{{ $fa1['club'] }}</div>@endif
                            </div>
                        </div>
                        <div class="spb-finals__player {{ ($fWinnerId && !$fa2IsPlaceholder && $fWinnerId == ($fa2['id'] ?? null)) ? 'spb-finals__player--winner' : '' }} {{ $fa2IsPlaceholder ? 'spb-finals__player--placeholder' : '' }}">
                            <span class="spb-finals__seed">2</span>
                            <div>
                                <div class="spb-finals__name">{{ $fa2['name'] ?? 'En attente' }}</div>
                                @if(!$fa2IsPlaceholder && !empty($fa2['club']))<div class="spb-finals__club">{{ $fa2['club'] }}</div>@endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>{{-- end pools --}}
        @endif

    </section>
    @endforeach
    @endif
</div>{{-- end content --}}

<div style="max-width:1280px;margin:0 auto;padding:16px 2rem;border-top:1px solid var(--spb-border);">
    <p style="font-size:12px;color:var(--spb-gray-500);margin:0;text-align:center;">
        {{ $event->name }} — Tirages officiels · {{ now()->format('d/m/Y') }}
    </p>
</div>

</div>{{-- end spb-page --}}

<script>
(function(){
    var input   = document.getElementById('spb-search');
    var clear   = document.getElementById('spb-search-clear');
    var results = document.getElementById('spb-search-results');
    var sections = document.querySelectorAll('.spb-section');
    var navItems = document.querySelectorAll('.spb-nav__item');
    if (!input) return;

    input.addEventListener('input', function() {
        var q = this.value.trim().toLowerCase();
        clear.style.display = q ? 'flex' : 'none';
        if (!q) {
            sections.forEach(function(s){ s.style.display=''; });
            navItems.forEach(function(n){ n.style.display=''; });
            results.style.display = 'none';
            return;
        }
        var visible = 0;
        sections.forEach(function(s, i) {
            var match = s.textContent.toLowerCase().includes(q);
            s.style.display = match ? '' : 'none';
            if (navItems[i]) navItems[i].style.display = match ? '' : 'none';
            if (match) visible++;
        });
        results.style.display = 'flex';
        results.textContent = visible === 0
            ? 'Aucun résultat pour "' + input.value.trim() + '"'
            : visible + ' catégorie' + (visible > 1 ? 's' : '') + ' sur ' + sections.length;
    });
})();

function spbClear() {
    var input = document.getElementById('spb-search');
    input.value = '';
    input.dispatchEvent(new Event('input'));
    input.focus();
}
</script>

</x-public-layout>
