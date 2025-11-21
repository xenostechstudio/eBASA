import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'from-rose-200', 'to-rose-400', 'text-slate-900',
        'from-amber-300', 'to-amber-400', 'text-slate-950',
        'from-sky-300', 'to-blue-400', 'text-slate-900',
        'from-purple-300', 'to-violet-400',
        'from-emerald-300', 'to-teal-400',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
