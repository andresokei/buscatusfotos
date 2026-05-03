/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                ink: '#0A0A0A',
                paper: '#FFFFFF',
                accent: {
                    DEFAULT: '#FF6B47',
                    hover: '#E8553A',
                },
            },
            fontFamily: {
                display: ['Fraunces', 'serif'],
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
            letterSpacing: {
                tightish: '-0.015em',
            },
            borderRadius: {
                card: '4px',
                btn: '2px',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
    ],
};
