import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#F0F4F9',
                    100: '#E6ECF4',
                    200: '#CBD7E7',
                    300: '#A5B8D3',
                    400: '#6F8DB8',
                    500: '#3A5F92',
                    600: '#1F3A5F',
                    700: '#1A3253',
                    800: '#152944',
                    900: '#101F34',
                    950: '#0A1423',
                },
                gray: {
                    50: '#F6F4EF',
                    100: '#EFECE4',
                    200: '#E3DFD5',
                    300: '#CFCABD',
                    400: '#A29D91',
                    500: '#6E6A60',
                    600: '#57534A',
                    700: '#433F38',
                    800: '#2E2B26',
                    900: '#1B1A17',
                    950: '#100F0D',
                },
            },
        },
    },
}
