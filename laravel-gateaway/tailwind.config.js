import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'coffee': 'var(--coffee)',
                'coffee-darker': 'var(--coffee-darker)',
                'coffee-lighter': 'var(--coffee-lighter)',
                'coffee-light': {
                    1: 'var(--coffee-light-1)',
                    2: 'var(--coffee-light-2)',
                    3: 'var(--coffee-light-3)',
                },
                'coffee-dark': {
                    0: 'var(--coffee-dark-0)',
                    1: 'var(--coffee-dark-1)',
                    2: 'var(--coffee-dark-2)',
                    3: 'var(--coffee-dark-3)',
                }
            }
        },
    },

    plugins: [forms],
};
