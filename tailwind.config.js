/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        navy: {
          800: '#1e293b', // Hover
          900: '#0f172a', // Navbar
          950: '#020617', // Footer
        },
        gold: {
          400: '#facc15', // Accents text
          500: '#eab308', // Icons
          600: '#ca8a04', // Buttons
        },
        parchment: {
          50: '#fdfbf7',  // Light bg for reading
          100: '#f6f1e3', // Card backgrounds
        }
      },
      fontFamily: {
        inter: ['Inter', 'sans-serif'],
        merriweather: ['Merriweather', 'serif'],
      }
    },
  },
  plugins: [],
}
