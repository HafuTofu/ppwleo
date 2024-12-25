/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./public/**/*.{html,js,php}','./atmin/**/*.{html,js,php}','./src/*.{html,js,php}','./atmin/*.{html,js,php}'],
  theme: {
    extend: {},
  },
  plugins: [require('tailwind-scrollbar-hide')],
}