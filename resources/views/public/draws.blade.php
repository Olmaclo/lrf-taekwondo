<x-public-layout :title="'Tirages — ' . $event->name" :description="'Tirages officiels de ' . $event->name">

<style>
/* ================================================================
   TKB Bracket System — dark / gold
   CSS variables utilisées par draw-bracket.blade.php + tkb-match-card
   ================================================================ */

:root {
    --gold:     #f59e0b;
    --goldbg:   rgba(245,158,11,0.08);
    --border:   rgba(255,255,255,0.09);
    --border2:  rgba(255,255,255,0.04);
    --r:        4px;
    --gray:     rgba(255,255,255,0.42);
    --gray2:    rgba(255,255,255,0.2);
    --line:     rgba(245,158,11,0.28);
    --line-ok:  rgba(34,197,94,0.4);
}

/* ── SECTION ─────────────────────────────────────────────────── */
.tkb-section { margin-bottom: 5rem; scroll-margin-top: 80px; }

.sec-hdr {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px; padding: 14px 0;
    margin-bottom: 18px; border-bottom: 1px solid var(--border);
}
.sec-title {
    font-size: 1.15rem; font-weight: 800; color: #fff; margin: 0;
    letter-spacing: -0.02em;
}
.g-m { color: #60a5fa; }
.g-f { color: #f472b6; }
.sec-sub {
    font-size: 0.65rem; color: var(--gray2); text-transform: uppercase;
    letter-spacing: 0.1em; margin-left: 10px;
}
.badge-admin {
    display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px;
    background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.3);
    border-radius: var(--r); font-size: 11px; font-weight: 700; color: var(--gold);
}
.badge-done {
    display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px;
    background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.25);
    border-radius: var(--r); font-size: 11px; font-weight: 700; color: #4ade80;
}

/* ── BRACKET OUTER ───────────────────────────────────────────── */
.bracket-outer {
    overflow-x: auto; padding: 12px 0 20px;
    -webkit-overflow-scrolling: touch;
}
.bracket-outer::-webkit-scrollbar { height: 5px; }
.bracket-outer::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
.bracket-outer::-webkit-scrollbar-thumb { background: rgba(245,158,11,0.3); border-radius: 3px; }

.bracket {
    display: flex; align-items: stretch; min-width: max-content;
}

.side-lbl {
    writing-mode: vertical-lr; transform: rotate(180deg);
    font-size: 0.5rem; font-weight: 700; color: rgba(255,255,255,0.06);
    text-transform: uppercase; letter-spacing: 0.2em; padding: 8px 5px;
    flex-shrink: 0; align-self: stretch; display: flex; align-items: center;
    justify-content: center;
}
.side-lbl--r { transform: rotate(0deg); }

/* ── SIDES ───────────────────────────────────────────────────── */
.bk-side { display: flex; align-items: stretch; }

/* ── COLUMN (un tour) ────────────────────────────────────────── */
.bk-col { display: flex; flex-direction: column; width: 252px; flex-shrink: 0; }
.bk-col--outer { width: 236px; }

.bk-col__lbl {
    height: 32px; display: flex; align-items: center; justify-content: center;
    font-size: 0.58rem; font-weight: 700; color: rgba(255,255,255,0.22);
    text-transform: uppercase; letter-spacing: 0.2em;
    border-bottom: 1px solid var(--border2); flex-shrink: 0;
}
.bk-col__body { flex: 1; display: flex; flex-direction: column; }

/* ── GRID SLOTS avec connecteurs (élimination directe) ───────── */
.bk-grid-slot {
    flex: 1; display: flex; align-items: center;
    position: relative; padding: 6px 0;
}

/* Côté GAUCHE : connecteurs sur la droite de la colonne */
.bk-grid-slot--bar-L::after {
    content: ""; position: absolute; right: 0; top: 50%;
    width: 20px; height: 1px; background: var(--line);
    transform: translateY(-0.5px);
}
.bk-grid-slot--bar-L::before {
    content: ""; position: absolute; right: 0; top: 50%;
    width: 1px; height: 50%; background: var(--line);
}
.bk-grid-slot--bar-L-even::after {
    content: ""; position: absolute; right: 0; top: 50%;
    width: 20px; height: 1px; background: var(--line);
    transform: translateY(-0.5px);
}
.bk-grid-slot--bar-L-even::before {
    content: ""; position: absolute; right: 0; top: 0;
    width: 1px; height: 50%; background: var(--line);
}

/* Côté DROIT : connecteurs sur la gauche */
.bk-grid-slot--bar-R::after {
    content: ""; position: absolute; left: 0; top: 50%;
    width: 20px; height: 1px; background: var(--line);
    transform: translateY(-0.5px);
}
.bk-grid-slot--bar-R::before {
    content: ""; position: absolute; left: 0; top: 50%;
    width: 1px; height: 50%; background: var(--line);
}
.bk-grid-slot--bar-R-even::after {
    content: ""; position: absolute; left: 0; top: 50%;
    width: 20px; height: 1px; background: var(--line);
    transform: translateY(-0.5px);
}
.bk-grid-slot--bar-R-even::before {
    content: ""; position: absolute; left: 0; top: 0;
    width: 1px; height: 50%; background: var(--line);
}

.bk-grid-slot.is-resolved::before,
.bk-grid-slot.is-resolved::after { background: var(--line-ok); }

/* ── SLOT (conteneur de la carte match) ─────────────────────── */
.bk-slot { width: 100%; padding: 0 8px; display: flex; align-items: center; }
.bk-slot--L { padding-right: 22px; }
.bk-slot--R { padding-left: 22px; }
.bk-slot--sL { padding-right: 22px; }
.bk-slot--sR { padding-left: 22px; }

/* ── CARTE MATCH ─────────────────────────────────────────────── */
.bk-card {
    width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--border);
    border-radius: var(--r); overflow: hidden; position: relative;
    transition: border-color 0.2s;
}
.bk-card:hover { border-color: rgba(245,158,11,0.3); }
.bk-card--resolved { border-color: rgba(34,197,94,0.2); }

.bk-reset-btn {
    position: absolute; top: 3px; right: 3px; z-index: 10;
    background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);
    border-radius: 3px; color: #ef4444; font-size: 10px; padding: 1px 4px;
    cursor: pointer; line-height: 1.4;
}

.bk-row {
    display: flex; align-items: center; gap: 7px; padding: 7px 9px;
    border-bottom: 1px solid var(--border2); transition: background 0.15s;
    min-height: 36px;
}
.bk-row:last-child { border-bottom: none; }
.bk-row--win { background: rgba(34,197,94,0.07); }
.bk-row--win .bk-name { color: #4ade80; font-weight: 700; }
.bk-row--tbd { opacity: 0.45; }
.bk-row--bye { opacity: 0.7; }
.bk-row--clickable { cursor: pointer; }
.bk-row--clickable:hover { background: rgba(245,158,11,0.07); }

.bk-seed {
    width: 20px; height: 20px; flex-shrink: 0; display: flex;
    align-items: center; justify-content: center;
    border: 1px solid var(--border); border-radius: 50%;
    font-size: 10px; font-weight: 700; color: var(--gold);
    background: rgba(245,158,11,0.06);
}
.bk-seed--tbd { color: var(--gray2); border-color: var(--border2); background: transparent; }
.bk-seed--bye { color: #4ade80; border-color: rgba(74,222,128,0.3); background: rgba(74,222,128,0.08); }

.bk-name {
    font-size: 0.76rem; font-weight: 600; color: #fff;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.bk-name--tbd { color: var(--gray2); font-style: italic; font-weight: 400; }
.bk-name--bye { color: #4ade80; }
.bk-club {
    font-size: 0.6rem; color: var(--gray2);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.bk-set-icon { font-size: 9px; color: rgba(245,158,11,0.5); flex-shrink: 0; margin-left: auto; }

/* ── CENTRE ──────────────────────────────────────────────────── */
.bk-center { display: flex; flex-direction: column; flex-shrink: 0; width: 196px; }
.bk-center-lbl-ph {
    height: 32px; display: flex; align-items: center; justify-content: center;
    border-bottom: 1px solid var(--border2);
}
.bk-center-body {
    flex: 1; display: flex; flex-direction: column; align-items: center;
    justify-content: center; padding: 16px 10px; gap: 10px;
}
.bk-center-top {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
}
.bk-event-badge {
    font-size: 0.5rem; font-weight: 700; color: rgba(245,158,11,0.45);
    text-transform: uppercase; letter-spacing: 0.22em; text-align: center;
    border: 1px solid rgba(245,158,11,0.14); padding: 3px 9px; border-radius: 2px;
}
.bk-trophy { display: flex; align-items: center; justify-content: center; }
.bk-cat {
    font-size: 0.56rem; color: rgba(255,255,255,0.28); text-align: center;
    text-transform: uppercase; letter-spacing: 0.1em; line-height: 1.6;
}
.bk-center-bottom { display: none; }

/* ── FINALE CENTRALE ─────────────────────────────────────────── */
.bk-finale {
    width: 100%; background: rgba(245,158,11,0.04);
    border: 1px solid rgba(245,158,11,0.2); border-radius: var(--r);
}
.bk-finale--resolved {
    border-color: rgba(245,158,11,0.45);
    box-shadow: 0 0 18px rgba(245,158,11,0.08);
}
.bk-finale__lbl {
    padding: 6px 10px; font-size: 0.58rem; font-weight: 800; color: var(--gold);
    text-align: center; text-transform: uppercase; letter-spacing: 0.22em;
    background: rgba(245,158,11,0.06); border-bottom: 1px solid rgba(245,158,11,0.14);
}
.bk-finale__row {
    display: flex; align-items: center; gap: 8px; padding: 7px 9px;
    border-bottom: 1px solid rgba(245,158,11,0.07);
}
.bk-finale__row:last-of-type { border-bottom: none; }
.bk-finale__row--win { background: rgba(34,197,94,0.08); }
.bk-finale__row--win .bk-finale__name { color: #4ade80; font-weight: 800; }
.bk-finale__row--tbd { opacity: 0.45; }
.bk-f-seed {
    width: 22px; height: 22px; flex-shrink: 0; display: flex; align-items: center;
    justify-content: center; border: 1px solid rgba(245,158,11,0.3); border-radius: 50%;
    font-size: 10px; font-weight: 800; color: var(--gold);
}
.bk-finale__name {
    font-size: 0.78rem; font-weight: 700; color: #fff;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.bk-finale__club { font-size: 0.6rem; color: var(--gray2); }
.bk-reset-finale {
    width: 100%; padding: 5px 10px; background: rgba(239,68,68,0.07);
    border: none; border-top: 1px solid rgba(239,68,68,0.18);
    color: #ef4444; font-size: 11px; cursor: pointer; text-align: center;
}
.bk-reset-finale:hover { background: rgba(239,68,68,0.14); }

/* ── CHAMPION ────────────────────────────────────────────────── */
.bk-champ {
    display: flex; align-items: center; gap: 14px; margin-top: 20px;
    padding: 14px 20px; background: rgba(245,158,11,0.05);
    border: 1px solid rgba(245,158,11,0.22); border-radius: var(--r);
}
.bk-champ__lbl {
    font-size: 0.56rem; font-weight: 700; color: rgba(245,158,11,0.5);
    text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 2px;
}
.bk-champ__name {
    font-size: 1.2rem; font-weight: 800; color: var(--gold); letter-spacing: -0.02em;
}

/* ── 3e PLACE ────────────────────────────────────────────────── */
.petite-finale {
    display: flex; align-items: center; flex-wrap: wrap; gap: 12px;
    margin-top: 12px; padding: 10px 16px;
    background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: var(--r);
}
.petite-finale__lbl {
    font-size: 0.58rem; font-weight: 700; color: rgba(255,255,255,0.28);
    text-transform: uppercase; letter-spacing: 0.15em; flex-shrink: 0;
}
.petite-finale__match { display: flex; align-items: center; gap: 10px; }
.petite-finale__name { font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.5); }
.petite-finale__name--win { color: var(--gold); font-weight: 700; }
.petite-finale__vs {
    font-size: 0.55rem; font-weight: 800; color: rgba(255,255,255,0.15); text-transform: uppercase;
}
.petite-finale__admin { display: flex; gap: 6px; margin-left: auto; }
.pf3-btn {
    padding: 4px 10px; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25);
    border-radius: 3px; color: var(--gold); font-size: 11px; cursor: pointer; font-family: inherit;
}
.pf3-btn:hover { background: rgba(245,158,11,0.2); }

/* ── POULES ──────────────────────────────────────────────────── */
.pools-section { margin-bottom: 24px; }
.pools-phase-hdr {
    display: flex; align-items: center; gap: 12px; padding: 12px 16px;
    background: rgba(255,255,255,0.02); border: 1px solid var(--border);
    border-radius: var(--r); margin-bottom: 16px;
}
.pools-phase-icon {
    width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
    background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2);
    border-radius: var(--r); color: var(--gold); flex-shrink: 0;
}
.pools-phase-title { font-size: 0.9rem; font-weight: 700; color: #fff; }
.pools-phase-sub { font-size: 0.7rem; color: var(--gray2); margin-top: 2px; }
.pools-phase-done {
    margin-left: auto; padding: 3px 10px; background: rgba(34,197,94,0.1);
    border: 1px solid rgba(34,197,94,0.25); border-radius: 20px;
    font-size: 0.7rem; font-weight: 700; color: #4ade80;
}
.pools-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(300px,1fr)); gap: 16px;
}
.pool-box {
    background: rgba(255,255,255,0.025); border: 1px solid var(--border);
    border-radius: var(--r); overflow: hidden;
}
.pool-box--done { border-color: rgba(34,197,94,0.18); }
.pool-box__hdr {
    display: flex; align-items: center; justify-content: space-between;
    padding: 9px 14px; border-bottom: 1px solid var(--border);
    background: rgba(255,255,255,0.02);
}
.pool-letter-badge {
    display: inline-flex; align-items: center; padding: 2px 9px;
    border-radius: 4px; font-size: 0.7rem; font-weight: 700;
}
.pool-box__cnt { font-size: 0.64rem; color: var(--gray2); }
.pool-box__done { font-size: 0.62rem; font-weight: 700; color: #4ade80; }
.pool-athletes { padding: 6px 0; border-bottom: 1px solid var(--border); }
.pool-athlete-row {
    display: flex; align-items: center; gap: 8px; padding: 5px 14px;
}
.pool-athlete-row--leader { background: rgba(245,158,11,0.04); }
.pool-athlete-rank { font-size: 0.68rem; font-weight: 800; width: 16px; flex-shrink: 0; }
.pool-aname { font-size: 0.78rem; font-weight: 600; color: #fff; }
.pool-aclub { font-size: 0.6rem; color: var(--gray2); }
.pool-wins-badge {
    margin-left: auto; padding: 1px 7px; border-radius: 20px;
    font-size: 0.6rem; font-weight: 800; color: var(--gray2);
    background: rgba(255,255,255,0.05);
}
.pool-wins-badge--pos { color: var(--gold); background: rgba(245,158,11,0.1); }
.pool-matches { padding: 10px 14px; }
.pool-mtitle {
    font-size: 0.57rem; font-weight: 700; color: var(--gray2); text-transform: uppercase;
    letter-spacing: 0.15em; margin: 0 0 8px;
}
.pool-match {
    display: flex; align-items: center; gap: 6px; padding: 4px 0;
    font-size: 0.76rem; border-bottom: 1px solid rgba(255,255,255,0.03);
}
.pool-match:last-child { border-bottom: none; }
.pool-match--done { opacity: 0.7; }
.pool-mnum {
    width: 17px; height: 17px; display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,0.04); border-radius: 50%;
    font-size: 9px; font-weight: 700; color: var(--gold); flex-shrink: 0;
}
.pool-mname {
    color: rgba(255,255,255,0.5); flex: 1; min-width: 0;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.pool-mname--w { color: #4ade80; font-weight: 700; }
.pool-mvs {
    font-size: 0.54rem; font-weight: 800; color: rgba(255,255,255,0.18); flex-shrink: 0;
}
.pool-match-btns { display: flex; gap: 4px; margin-left: auto; }
.pmb {
    padding: 2px 7px; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25);
    border-radius: 3px; color: var(--gold); font-size: 10px; cursor: pointer; font-family: inherit;
}
.pmb--reset { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.2); color: #ef4444; }

/* ── PHASE FINALE (format poules) ───────────────────────────── */
.finale-phase-wrapper { margin-top: 24px; }
.finale-phase-hdr {
    display: flex; align-items: center; gap: 12px; padding: 12px 16px;
    background: rgba(245,158,11,0.04); border: 1px solid rgba(245,158,11,0.15);
    border-radius: var(--r); margin-bottom: 16px;
}

/* ── COLONNES POULES BRACKET ─────────────────────────────────── */
.bk-pool-outer { display: flex; align-items: center; flex-shrink: 0; padding: 40px 0; }
.bk-pool-grp { display: flex; flex-direction: column; gap: 10px; padding: 0 8px; }
.bk-pool-mc {
    background: rgba(255,255,255,0.025); padding: 7px 10px;
    min-width: 116px; border-radius: 3px;
}
.bk-pool-mc__lbl {
    font-size: 0.58rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: 0.12em; margin-bottom: 5px;
}
.bk-pool-mc__ath { font-size: 0.68rem; color: rgba(255,255,255,0.38); padding: 2px 0; }
.bk-pool-mc__ath--q { color: var(--gold); font-weight: 600; }

/* ── ESPACEMENT POULES BRACKET ───────────────────────────────── */
.bk-spacer { flex: 1; min-height: 8px; }
.bk-game-spacer { position: relative; min-height: 20px; flex-shrink: 0; }
.bk-game-spacer--L::after {
    content: ""; position: absolute; right: 0;
    top: 0; bottom: 0; width: 1px; background: var(--line);
}
.bk-game-spacer--R::after {
    content: ""; position: absolute; left: 0;
    top: 0; bottom: 0; width: 1px; background: var(--line);
}
.bk-game-spacer.is-resolved::after { background: var(--line-ok); }
.bk-inter-spacer { min-height: 14px; flex-shrink: 0; }

/* ── IMPRESSION ──────────────────────────────────────────────── */
@media print {
    @page { size: A4 landscape; margin: 10mm; }

    /* Masquer absolument tout sauf les brackets */
    #navbar,
    #mobile-menu,
    #footer,
    #flash-toast,
    .tkb-no-print { display: none !important; }

    html, body {
        background: #fff !important;
        padding-top: 0 !important;
        margin: 0 !important;
    }
    #tkb-draws-page {
        background: #fff !important; color: #000 !important;
        padding-top: 0 !important; font-size: 11px !important;
    }
    .tkb-section { page-break-after: always; break-after: page; }
    .tkb-section:last-child { page-break-after: avoid; break-after: avoid; }

    /* Couleurs lisibles sur blanc */
    .bk-card  { border: 1px solid #ccc !important; background: #f9f9f9 !important; }
    .bk-row   { border-color: #e0e0e0 !important; background: transparent !important; }
    .bk-row--win { background: #e8f5e9 !important; }
    .bk-name  { color: #111 !important; }
    .bk-club  { color: #333 !important; font-weight: 700 !important; }          /* ← clubs en gras */
    .bk-seed  { border-color: #bbb !important; color: #c07000 !important; background: #fffbe8 !important; }

    .sec-title  { color: #000 !important; }
    .sec-hdr    { border-color: #ccc !important; }
    .sec-sub    { color: #555 !important; }
    .badge-done { color: #2a7a2a !important; background: #e8f5e9 !important; border-color: #a5d6a7 !important; }

    .bk-col__lbl    { color: #444 !important; border-color: #e0e0e0 !important; }
    .bk-event-badge { color: #888 !important; border-color: #ccc !important; }
    .bk-cat         { color: #444 !important; }
    .bk-trophy svg  { stroke: #c07000 !important; }
    .g-m { color: #1565c0 !important; }
    .g-f { color: #880e4f !important; }

    .bk-finale       { border-color: #ccc !important; background: #fffdf5 !important; }
    .bk-finale__lbl  { color: #7a5000 !important; background: #fff8e1 !important; border-color: #ffe082 !important; }
    .bk-finale__name { color: #111 !important; }
    .bk-finale__club { color: #333 !important; font-weight: 700 !important; }  /* ← clubs finale en gras */
    .bk-f-seed       { border-color: #f9a825 !important; color: #f57f17 !important; }
    .bk-reset-finale { display: none !important; }

    .bk-champ      { background: #fffde7 !important; border-color: #f9a825 !important; }
    .bk-champ__lbl { color: #c07000 !important; }
    .bk-champ__name { color: #7a4000 !important; }

    .bracket-outer { overflow: visible !important; }

    /* Les lignes du bracket restent visibles */
    :root {
        --line:    rgba(200,140,0,0.5) !important;
        --line-ok: rgba(0,140,0,0.4) !important;
    }
}
</style>

<div id="tkb-draws-page" style="background:#0a0a0f;min-height:100vh;padding-top:80px;font-family:'Space Grotesk','Inter',system-ui,sans-serif;color:#fff;">

{{-- ── HERO ─────────────────────────────────────────────────────────────────── --}}
<div class="tkb-no-print" style="position:relative;overflow:hidden;background:#0a0a0f;border-bottom:1px solid rgba(245,158,11,0.1);">
    <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(245,158,11,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(245,158,11,0.02) 1px,transparent 1px);background-size:56px 56px;pointer-events:none;"></div>
    <div style="position:absolute;top:-60px;left:50%;transform:translateX(-50%);width:700px;height:320px;background:radial-gradient(ellipse,rgba(245,158,11,0.07) 0%,transparent 65%);pointer-events:none;"></div>

    <div style="max-width:1400px;margin:0 auto;padding:5rem 2.5rem 4rem;position:relative;">
        <a href="{{ route('public.event-detail', $event->slug) }}"
           style="display:inline-flex;align-items:center;gap:8px;color:rgba(255,255,255,0.28);font-size:0.68rem;text-decoration:none;margin-bottom:3rem;text-transform:uppercase;letter-spacing:0.14em;transition:color 0.2s;"
           onmouseover="this.style.color='rgba(245,158,11,0.8)'" onmouseout="this.style.color='rgba(255,255,255,0.28)'">
            <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            {{ $event->name }}
        </a>

        <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:2rem;flex-wrap:wrap;">
            <div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:1.1rem;">
                    <div style="width:28px;height:2px;background:#f59e0b;"></div>
                    <span style="font-size:0.58rem;font-weight:700;color:#f59e0b;letter-spacing:0.32em;text-transform:uppercase;">Tirages officiels</span>
                    <div style="width:28px;height:2px;background:#f59e0b;"></div>
                </div>
                <h1 style="font-size:clamp(2rem,5vw,3.8rem);font-weight:900;color:#fff;line-height:1;letter-spacing:-0.04em;margin:0 0 1rem;text-transform:uppercase;">
                    {{ $event->name }}
                </h1>
                <div style="display:flex;gap:20px;flex-wrap:wrap;align-items:center;">
                    <span style="font-size:0.62rem;color:rgba(255,255,255,0.3);letter-spacing:0.08em;">
                        <b style="color:rgba(245,158,11,0.8);">{{ $draws->count() }}</b> catégorie(s)
                    </span>
                    <span style="font-size:0.62rem;color:rgba(255,255,255,0.3);letter-spacing:0.08em;">
                        <b style="color:rgba(245,158,11,0.8);">{{ $draws->sum('total_athletes') }}</b> athlète(s)
                    </span>
                </div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <a href="{{ route('public.athlete-list', $event->slug) }}"
                   style="display:inline-flex;align-items:center;gap:8px;color:rgba(255,255,255,0.45);font-size:0.68rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;border:1px solid rgba(255,255,255,0.1);padding:11px 22px;transition:all 0.2s;"
                   onmouseover="this.style.color='#f59e0b';this.style.borderColor='rgba(245,158,11,0.45)'" onmouseout="this.style.color='rgba(255,255,255,0.45)';this.style.borderColor='rgba(255,255,255,0.1)'">
                    Liste des athlètes
                </a>
                <button onclick="tkbPrint()"
                        style="display:inline-flex;align-items:center;gap:8px;background:rgba(245,158,11,0.1);color:#f59e0b;font-size:0.68rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;border:1px solid rgba(245,158,11,0.35);padding:11px 22px;cursor:pointer;font-family:inherit;transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(245,158,11,0.18)'" onmouseout="this.style.background='rgba(245,158,11,0.1)'">
                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── NAVIGATION RAPIDE ──────────────────────────────────────────────────────── --}}
@if($draws->isNotEmpty())
<nav class="tkb-no-print" style="background:#0d0d12;border-bottom:1px solid rgba(255,255,255,0.06);overflow-x:auto;">
    <div style="max-width:1400px;margin:0 auto;padding:10px 2.5rem;display:flex;gap:8px;flex-wrap:nowrap;min-width:max-content;">
        @foreach($draws as $draw)
        <a href="#cat-{{ $draw->id }}"
           style="display:flex;align-items:center;gap:7px;padding:7px 14px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);color:rgba(255,255,255,0.55);font-size:0.68rem;font-weight:600;text-decoration:none;white-space:nowrap;transition:all 0.2s;"
           onmouseover="this.style.borderColor='rgba(245,158,11,0.4)';this.style.color='#f59e0b'" onmouseout="this.style.borderColor='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.55)'">
            <span style="width:6px;height:6px;border-radius:50%;background:rgba(245,158,11,0.5);flex-shrink:0;"></span>
            {{ $draw->age_category }} {{ $draw->weight_category }}
            <span style="font-size:0.58rem;color:rgba(255,255,255,0.25);">({{ $draw->total_athletes }})</span>
        </a>
        @endforeach
    </div>
</nav>
@endif

{{-- ── CONTENU PRINCIPAL ──────────────────────────────────────────────────────── --}}
<div style="max-width:1400px;margin:0 auto;padding:3rem 2.5rem 7rem;">

    @if($draws->isEmpty())
    <div style="padding:8rem 0;text-align:center;">
        <svg style="width:52px;height:52px;color:rgba(255,255,255,0.06);margin:0 auto 1.5rem;display:block;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18m-7 5h7"/></svg>
        <p style="color:rgba(255,255,255,0.2);font-size:0.875rem;letter-spacing:0.06em;">Les tirages au sort n'ont pas encore été effectués pour cet événement.</p>
    </div>
    @else
    @foreach($draws as $draw)
    @include('_partials.draw-bracket', ['draw' => $draw, 'event' => $event, 'isAdmin' => false])
    @endforeach
    @endif

</div>

</div>{{-- /tkb-draws-page --}}

<script>
/* ── Impression : mise à l'échelle dynamique du bracket ───────────────── */
(function () {
    var _saved = [];

    /* A4 paysage à 96 dpi = 1123 px, marges 10mm × 2 = 76 px → ~1047 px utiles.
       On prend 980 px pour garder une marge de sécurité (certains drivers ajoutent
       des en-têtes/pieds de page supplémentaires). */
    var PAGE_W = 980;

    function applyScale() {
        _saved = [];
        document.querySelectorAll('.tkb-section').forEach(function (section) {
            var outer   = section.querySelector('.bracket-outer');
            var bracket = section.querySelector('.bracket');
            if (!bracket) { _saved.push(null); return; }

            /* Mesure la largeur réelle du bracket (sans masque overflow) */
            if (outer) outer.style.overflow = 'visible';
            var needed = bracket.scrollWidth;
            if (outer) outer.style.overflow = '';

            var scale = (needed > PAGE_W) ? (PAGE_W / needed) : 1;
            _saved.push({ el: bracket, prev: bracket.style.zoom || '' });

            bracket.style.zoom = scale < 1 ? scale.toFixed(5) : '';
        });
    }

    function resetScale() {
        _saved.forEach(function (entry) {
            if (entry) entry.el.style.zoom = entry.prev;
        });
        _saved = [];
    }

    window.tkbPrint = function () {
        applyScale();
        /* Petit délai pour que le navigateur applique le zoom avant d'ouvrir
           la boîte d'impression (nécessaire sur certains navigateurs). */
        setTimeout(function () { window.print(); }, 80);
    };

    window.addEventListener('afterprint', resetScale);
})();
</script>

</x-public-layout>
