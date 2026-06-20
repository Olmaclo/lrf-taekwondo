/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/public/**/*.blade.php',
        './resources/views/auth/**/*.blade.php',
        './resources/views/components/public-layout.blade.php',
        './resources/views/errors/**/*.blade.php',
        './resources/views/welcome.blade.php',
        './resources/views/vendor/pagination/**/*.blade.php',
        './resources/js/app.js',
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    50:  '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                surface: {
                    50:  '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#080d17',
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
                mono: ['JetBrains Mono', 'monospace'],
            },
            boxShadow: {
                'glow-gold':    '0 0 20px rgba(245, 158, 11, 0.3)',
                'glow-gold-sm': '0 0 10px rgba(245, 158, 11, 0.2)',
                'card':         '0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -2px rgba(0, 0, 0, 0.3)',
                'card-hover':   '0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -4px rgba(0, 0, 0, 0.3)',
            },
            animation: {
                'fade-in':    'fadeIn 0.3s ease-in-out',
                'slide-in':   'slideIn 0.3s ease-out',
                'pulse-gold': 'pulseGold 2s ease-in-out infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%':   { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideIn: {
                    '0%':   { transform: 'translateY(-10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)',     opacity: '1' },
                },
                pulseGold: {
                    '0%, 100%': { boxShadow: '0 0 0 0 rgba(245, 158, 11, 0.4)' },
                    '50%':      { boxShadow: '0 0 0 8px rgba(245, 158, 11, 0)' },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
