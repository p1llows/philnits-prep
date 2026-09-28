/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#f0f4f8',
                    100: '#d9e4f1',
                    200: '#bcccdb',
                    300: '#a0b0c3',
                    400: '#8694ac',
                    500: '#6a7895',
                    600: '#52607a',
                    700: '#414d62',
                    800: '#364050',
                    900: '#2f3845',
                },
                secondary: {
                    50: '#f8f9fa',
                    100: '#eff1f3',
                    200: '#dee2e6',
                    300: '#ced4da',
                    400: '#adb5bd',
                    500: '#868e96',
                    600: '#6c757d',
                    700: '#545b62',
                    800: '#494e53',
                    900: '#3d4144',
                },
                success: {
                    500: '#28a745',
                    600: '#218838',
                },
                warning: {
                    500: '#ffc107',
                    600: '#e0a800',
                },
                error: {
                    500: '#dc3545',
                    600: '#c82333',
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
                serif: ['Georgia', 'serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
