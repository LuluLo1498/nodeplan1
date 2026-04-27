import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        'playwrite': ['"Playwrite IE"', 'cursive'],
        'gummy': ['"Sour Gummy"', 'sans-serif'],
      },
      colors: {
        verdes: {
          DEFAULT: '#8EC156',
          light: '#E1F2B3',
          dark: '#594E2D',
        },
        marron: '#734432',
        fondo: '#F2DEC4',
      },
    },
  },
  plugins: [],
}