import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ── Temps réel (Laravel Echo + Pusher) ─────────────────────────────────────────
// Initialisé seulement si les clés Pusher sont présentes au build. Sinon le chat
// bascule automatiquement sur un rafraîchissement périodique (fallback polling).
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

if (import.meta.env.VITE_PUSHER_APP_KEY) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'eu',
        forceTLS: true,
        enabledTransports: ['ws', 'wss'],
    });
}
