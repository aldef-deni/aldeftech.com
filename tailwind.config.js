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
                    bg: '#FFFFFF',
                    surface: '#FFFFFF',
                    'surface-2': '#F8FAFC',
                    'surface-3': '#F1F5F9',
                    elevated: '#FFFFFF',
                    border: '#E2E8F0',
                    'border-light': '#F1F5F9',
                    'border-hover': '#CBD5E1',
                },
                text: {
                    primary: '#0F172A',
                    secondary: '#475569',
                    muted: '#64748B',
                    dark: '#94A3B8',
                },
                accent: {
                    DEFAULT: '#2563EB',
                    hover: '#1D4ED8',
                    light: '#3B82F6',
                    dark: '#1E40AF',
                    muted: 'rgba(37, 99, 235, 0.06)',
                    'muted-2': 'rgba(37, 99, 235, 0.12)',
                    glow: 'rgba(37, 99, 235, 0.2)',
                },
                dark: {
                    bg: '#090D16',
                    surface: '#0F172A',
                    'surface-2': '#1E293B',
                    border: '#1E293B',
                    text: '#F8FAFC',
                    muted: '#94A3B8',
                },
                success: { DEFAULT: '#10B981', dark: '#059669' },
                warning: { DEFAULT: '#F59E0B', dark: '#D97706' },
                danger: { DEFAULT: '#EF4444', dark: '#DC2626' },
                info: { DEFAULT: '#0284C7', dark: '#0369A1' },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Space Grotesk', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                'display-xl': ['4.25rem', { lineHeight: '1.06', letterSpacing: '-0.04em', fontWeight: '800' }],
                'display': ['3.25rem', { lineHeight: '1.1', letterSpacing: '-0.035em', fontWeight: '700' }],
                'display-sm': ['2.5rem', { lineHeight: '1.15', letterSpacing: '-0.03em', fontWeight: '700' }],
                'heading': ['1.875rem', { lineHeight: '1.25', letterSpacing: '-0.02em', fontWeight: '600' }],
                'heading-sm': ['1.375rem', { lineHeight: '1.35', letterSpacing: '-0.015em', fontWeight: '600' }],
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
                'soft': '0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04)',
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -2px rgba(0, 0, 0, 0.04)',
                'card-hover': '0 20px 35px -5px rgba(37, 99, 235, 0.08), 0 8px 16px -6px rgba(15, 23, 42, 0.05)',
                'elevated': '0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(15, 23, 42, 0.05)',
                'glow': '0 0 25px rgba(37, 99, 235, 0.18)',
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
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.97)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                glowPulse: {
                    '0%, 100%': { boxShadow: '0 0 20px rgba(37, 99, 235, 0.1)' },
                    '50%': { boxShadow: '0 0 35px rgba(37, 99, 235, 0.2)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-6px)' },
                },
            },
        },
    },

    plugins: [],
};
