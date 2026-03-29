/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/tailwind-light/**/*.blade.php',
    './src/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a5f',
          950: '#172554',
        },
        surface: {
          light: 'rgba(255, 255, 255, 0.65)',
          DEFAULT: 'rgba(255, 255, 255, 0.75)',
          dark: 'rgba(255, 255, 255, 0.85)',
        },
        sidebar: {
          DEFAULT: 'rgba(15, 23, 42, 0.88)',
          hover: 'rgba(30, 41, 59, 0.9)',
          active: 'rgba(51, 65, 85, 0.9)',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
      },
      backdropBlur: {
        glass: '16px',
        'glass-lg': '24px',
      },
      boxShadow: {
        glass: '0 8px 32px rgba(0, 0, 0, 0.08)',
        'glass-lg': '0 12px 48px rgba(0, 0, 0, 0.12)',
        'glass-inset': 'inset 0 1px 1px rgba(255, 255, 255, 0.4)',
        glow: '0 0 20px rgba(59, 130, 246, 0.3)',
      },
      borderRadius: {
        glass: '1rem',
        'glass-lg': '1.5rem',
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-out',
        'slide-in': 'slideIn 0.3s ease-out',
        'slide-down': 'slideDown 0.2s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideIn: {
          '0%': { opacity: '0', transform: 'translateX(-10px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' },
        },
        slideDown: {
          '0%': { opacity: '0', transform: 'translateY(-5px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
