/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.php",
    "./pages/**/*.php",
    "./includes/**/*.php",
    "./actions/**/*.php"
  ],
  theme: {
    extend: {
      colors: { primary: '#7c2d12', secondary: '#b45309', }
    },
  },
  plugins: [],
}