module.exports = {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
    "./resources/css/**/*.css",
  ],
  safelist: [
    'lg:flex', 'lg:hidden', 'lg:gap-8'
  ],
  theme: {
    extend: {
      fontFamily: {
        'poppins': ['Poppins', 'sans-serif'],
      },
      backgroundImage: {
        'grad-lr-soft': 'linear-gradient(to right, rgba(21,94,160,0.15), rgba(255,255,255,1))',
        'grad-rl-soft': 'linear-gradient(to left, rgba(21,94,160,0.15), rgba(255,255,255,1))',
      },
      keyframes: {
        slide: {
          '0%': { transform: 'translateX(100%)' },
          '100%': { transform: 'translateX(-100%)' }
        },
      },
      animation: {
        slide: 'slide 15s linear infinite',
      },
      colors: {
        primary: "#155EA0",
        primaryDark: "#0f4a7d",
        accent: "#19A7CE",
        night: "#0b1220",
      },
    },
  },
  plugins: [],
}
