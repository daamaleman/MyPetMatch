import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        poppins: ['Poppins', ...defaultTheme.fontFamily.sans],
        inter: ['Inter', ...defaultTheme.fontFamily.sans],
        sans: ['Poppins', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        primary: {
          DEFAULT: '#05706C',   // verde principal
          light: '#187056',     // variante verde más oscura
        },
        secondary: {
          DEFAULT: '#F57952',   // naranja principal
        },
        neutral: {
          light: '#FAEFEA',     // fondo claro
          dark: '#23292C',      // gris/negro oscuro
          mid: '#C9D1D9',       // gris medio
          white: '#FFFFFF',     // blanco
        },
        warning: {
          DEFAULT: '#EFA71D',   // amarillo
        },
        danger: {
          DEFAULT: '#E03A2B',   // rojo
        },
      },
      borderRadius: {
        xl: '0.75rem',
        '2xl': '1rem',
      },
      boxShadow: {
        card: '0 4px 18px rgba(0,0,0,0.08)',
      },
    },
  },
  plugins: [forms],
}
