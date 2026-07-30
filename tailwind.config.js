import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
                script: ['"Great Vibes"', 'cursive'],
            },
            colors: {
                // Deep Georgian-wine burgundy — the logo's red lettering / mascot.
                wine: {
                    50: '#fbf1f1',
                    100: '#f4dcdd',
                    200: '#e6b6b8',
                    300: '#d38a8e',
                    400: '#bc5a60',
                    500: '#9c3d44',
                    600: '#7f2c33',
                    700: '#611f26',
                    800: '#4a171d',
                    900: '#331014',
                    950: '#210a0c',
                },
                // Warm amber — gold accents, seats-left badges, seals.
                gold: {
                    50: '#fdf8ee',
                    100: '#f9edd0',
                    200: '#f2d89e',
                    300: '#e9bd63',
                    400: '#e1a53a',
                    500: '#cc8a25',
                    600: '#a76a1c',
                    700: '#824f1a',
                    800: '#6a401b',
                    900: '#59361a',
                },
                // The logo's cream/paper background — the whole site sits on this.
                cream: {
                    50: '#fdfbf6',
                    100: '#faf5ea',
                    200: '#f3ecdb',
                    300: '#ece0c8',
                    400: '#e0cfac',
                    500: '#d0b98a',
                },
                // Warm near-black for body text on cream (softer than pure stone-900).
                ink: {
                    700: '#4a3f38',
                    800: '#342b25',
                    900: '#241d19',
                },
            },
            boxShadow: {
                soft: '0 2px 8px -2px rgb(52 43 37 / 0.08), 0 12px 32px -12px rgb(52 43 37 / 0.16)',
                lift: '0 8px 24px -8px rgb(97 31 38 / 0.28)',
            },
            keyframes: {
                'rise-in': {
                    '0%': { opacity: '0', transform: 'translateY(24px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'pour': {
                    '0%, 100%': { transform: 'rotate(-3deg)' },
                    '50%': { transform: 'rotate(3deg)' },
                },
                'float-slow': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
            },
            animation: {
                'float-slow': 'float-slow 6s ease-in-out infinite',
                'pour': 'pour 4s ease-in-out infinite',
            },
        },
    },

    plugins: [forms],
};
