/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.{html,js}",
    "./app/**/*.{html,js,php}",
    // "./app/**/.{html,js,php}",
  ],
  theme: {
    extend: {
      fontFamily: {
        nunito: ['Nunito', 'sans-serif'],
        kanit: ['Kanit', 'sans-serif'],
        bree: ['Bree Serif', 'serif'],
        secularOne: ['Secular One', 'sans-serif'],
        poppins: ['Poppins', 'sans-serif'],
        rubik: ['Rubik', 'sans-serif']
      },
      colors : {
        "biru" : '#47B5FF',
        "abu" : '#0B212F',
        "dark-white" : '#EEEFFF',
        "kuning" : '#FFCE00',
        "ungu" : '#7747FF',
        "merah" : '#DC2323',
      },
      backgroundImage : {
        "hero-bg" : "url('/public/Template/img/bg/Background.png')",
        "nailart-bg" : "url('http://localhost/images/bg/Background.png')"
      }
    },
  },
  plugins: [
    // require("daisyui"),
    // require('@tailwindcss/forms')
  ],
}

