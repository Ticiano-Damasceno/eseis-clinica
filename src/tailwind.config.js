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
                display: ['"Playfair Display"', 'serif'],
                sans: ['Montserrat', 'sans-serif'],
            },
            colors: {
                eseis: {
                    yellow: '#ffde59',
                    orange: '#eb6024',
                    beige: '#f6dfba',
                    tan: '#d8b38a',
                    terracotta: '#d9874f',
                    olive: '#828a69',
                    brick: '#c16146',
                },
            },
        },
    },

    plugins: [forms],
};
