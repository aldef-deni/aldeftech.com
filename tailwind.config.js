import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    bg: '#07080A',
                    surface: '#0E1015',
                    'surface-2': '#14161C',
                    'surface-3': '#1A1D25',
                    elevated: '#1F2230',
                    border: '#1E2130',
                    'border-light': '#282C3A',
                },
                text: {
                    primary: '#F0F0F5',
                    secondary: '#B8BAC4',
                    muted: '#7C7F8E',
                    dark: '#4E5162',
                },
                accent: {
                    DEFAULT: '#7C5CFC',
                    hover: '#6A48E8',
                    light: '#9B80FD',
                    dark: '#5A38D1',
                    muted: 'rgba(124, 92, 252, 0.08)',
                    'muted-2': 'rgba(124, 92, 252, 0.15)',
                    glow: 'rgba(124, 92, 252, 0.25)',
                },
                success: { DEFAULT: '#34D399', dark: '#059669' },
                warning: { DEFAULT: '#FBBF24', dark: '#D97706' },
                danger: { DEFAULT: '#F87171', dark: '#DC2626' },
                info: { DEFAULT: '#22D3EE', dark: '#0891B2' },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Space Grotesk', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                'display-xl': ['4.5rem', { lineHeight: '1.05', letterSpacing: '-0.035em', fontWeight: '700' }],
                'display': ['3.5rem', { lineHeight: '1.08', letterSpacing: '-0.03em', fontWeight: '700' }],
                'display-sm': ['2.75rem', { lineHeight: '1.12', letterSpacing: '-0.025em', fontWeight: '600' }],
                'heading': ['2rem', { lineHeight: '1.25', letterSpacing: '-0.015em', fontWeight: '600' }],
                'heading-sm': ['1.5rem', { lineHeight: '1.3', letterSpacing: '-0.01em', fontWeight: '600' }],
                'body-lg': ['1.125rem', { lineHeight: '1.75' }],
                'body': ['1rem', { lineHeight: '1.7' }],
                'body-sm': ['0.875rem', { lineHeight: '1.6' }],
                'caption': ['0.75rem', { lineHeight: '1.5' }],
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            },
            maxWidth: {
                '8xl': '88rem',
                '7xl': '80rem',
            },
            borderRadius: {
                'xl': '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },
            boxShadow: {
                'glow': '0 0 20px rgba(124, 92, 252, 0.15)',
                'glow-lg': '0 0 40px rgba(124, 92, 252, 0.2)',
                'glow-xl': '0 0 60px rgba(124, 92, 252, 0.25)',
                'card': '0 2px 8px -2px rgba(0, 0, 0, 0.3), 0 4px 16px -4px rgba(0, 0, 0, 0.2)',
                'card-hover': '0 12px 40px -8px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(124, 92, 252, 0.08)',
                'elevated': '0 20px 60px -12px rgba(0, 0, 0, 0.5)',
            },
            animation: {
                'fade-in': 'fadeIn 0.6s ease-out forwards',
                'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                'fade-in-down': 'fadeInDown 0.6s ease-out forwards',
                'slide-in-left': 'slideInLeft 0.6s ease-out forwards',
                'slide-in-right': 'slideInRight 0.6s ease-out forwards',
                'scale-in': 'scaleIn 0.5s ease-out forwards',
                'glow-pulse': 'glowPulse 4s ease-in-out infinite',
                'float': 'float 6s ease-in-out infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(24px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-24px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(24px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.96)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                glowPulse: {
                    '0%, 100%': { boxShadow: '0 0 20px rgba(124, 92, 252, 0.1)' },
                    '50%': { boxShadow: '0 0 40px rgba(124, 92, 252, 0.2)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-8px)' },
                },
            },
        },
    },

    plugins: [],
};
