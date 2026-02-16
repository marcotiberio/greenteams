/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    '*.php',
    'templates/**/*.{php,twig}',
    './Components/**/*.{php,twig}'
  ],
  theme: {
    borderWidth: {
      DEFAULT: '1px',
      0: '0',
      2: '2px',
      3: '3px'
    },
    borderRadius: {
      none: '0',
      DEFAULT: '0',
      image: '10px',
      container: '20px',
      button: '50px',
      full: '9999px'
    },
    colors: {
      white: '#fffeeB',
      black: '#303030',
      yellow: '#ffdd47',
      orange: '#ff904f',
      green: '#7bdb8f',
      beige: '#f6f4ca',
      blue: '#6690ff',
      lightblue: '#b1d1e4',
      grey: '#f9f9f9',
      darkgrey: '#28321E',
      acqua: '#51bad9',
      azulgrey: '#e6ecee',
      current: 'currentColor',
      transparent: 'transparent',
      blog: '#7bdb8f',
      event: '#6690ff',
      lernen: '#ff904f'
    },
    fontFamily: {
      sans: ['PlusJakartaSans', 'Arial', 'sans-serif'],
      serif: ['FacultyGlyphic', 'Arial', 'sans-serif']
    },
    fontSize: {
      body: ['1.5rem'],
      bodySmall: ['0.875rem'],
      button: ['1rem'],
      heroTitle: ['5rem'],
      heroSubtitle: ['3.125rem'],
      h1: ['2.25rem'],
      h2: ['1.75rem'],
      h3: ['1.25rem'],
      bodyMenu: ['1rem']
    },
    screens: {
      sm: '640px',
      md: '780px',
      lg: '1180px',
      xl: '1440px',
      max: '1680px'
    },
    extend: {
      aspectRatio: {
        '16/6': '16 / 6',
        '9/16': '9 / 16',
        '4/3': '4 / 3',
        '3/4': '3 / 4',
        '2/1': '2 / 1'
      },
      borderWidth: {
        DEFAULT: '1px',
        0: '0',
        2: '2px',
        3: '3px',
        4: '4px'
      },
      spacing: {
        xs: '15px',
        sm: '30px',
        md: '50px',
        lg: '80px',
        xl: '100px',
        xxl: '150px'
      }
    }
  },
  plugins: []
}
