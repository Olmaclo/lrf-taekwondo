import './bootstrap';
import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';
import Focus from '@alpinejs/focus';
import Chart from 'chart.js/auto';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

// ── Public page animations ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Only run on public pages (body has no sidebar)
    if (document.querySelector('.sidebar-el')) return;

    // Stats counter animation only — no opacity/transform animations to avoid flash
    gsap.utils.toArray('[data-gsap="counter"]').forEach(el => {
        const target = parseInt(el.dataset.target || el.textContent, 10);
        if (isNaN(target)) return;
        const obj = { val: 0 };
        gsap.to(obj, {
            scrollTrigger: { trigger: el, start: 'top 90%', once: true },
            val: target, duration: 1.8, ease: 'power2.out',
            onUpdate() { el.textContent = Math.round(obj.val).toLocaleString('fr-FR'); },
        });
    });
});

// Alpine plugins
Alpine.plugin(Collapse);
Alpine.plugin(Focus);

// Global helpers accessible in Alpine
Alpine.store('toast', {
    items: [],
    show(message, type = 'info', duration = 4000) {
        const id = Date.now();
        this.items.push({ id, message, type });
        setTimeout(() => this.remove(id), duration);
    },
    remove(id) {
        this.items = this.items.filter(t => t.id !== id);
    },
    success(msg)  { this.show(msg, 'success'); },
    error(msg)    { this.show(msg, 'error'); },
    info(msg)     { this.show(msg, 'info'); },
    warning(msg)  { this.show(msg, 'warning'); },
});

// Global API helper
window.api = {
    async get(url, params = {}) {
        const qs = new URLSearchParams(params).toString();
        const res = await fetch(qs ? `${url}?${qs}` : url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            cache: 'no-store',
        });
        return res.json();
    },
    async post(url, data = {}) {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
        });
        return res.json();
    },
    async put(url, data = {}) {
        const res = await fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
        });
        return res.json();
    },
    async delete(url) {
        const res = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        });
        return res.json();
    },
};

// Chart.js global defaults for dark theme
Chart.defaults.color           = '#94a3b8';
Chart.defaults.borderColor     = '#334155';
Chart.defaults.backgroundColor = 'rgba(245, 158, 11, 0.15)';

// Make Chart available globally for inline scripts
window.Chart = Chart;

window.Alpine = Alpine;
Alpine.start();
