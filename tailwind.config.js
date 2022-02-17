const defaultTheme = require('tailwindcss/defaultTheme')
const {colors} = defaultTheme

module.exports = {
    purge: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        minWidth: {
            ...defaultTheme.minWidth,
            '56': '14rem',
            '64': '16rem'
        },
        boxShadow: {
            ...defaultTheme.boxShadow,
            default: '0 0 10px 0 #E0E0E0',
            '3lg': '1px 1px 10px 0px rgba(0, 0, 0, 0.1), -1px 1px 10px 0px rgba(0, 0, 0, 0.05);',
        },
        borderWidth: {
            ...defaultTheme.borderWidth,
            '3': '3px',
            '20': '20px',
        },
        extend: {
            zIndex: {
                ...defaultTheme.zIndex,
                '-1': '-1',
                '60': '60',
                '100': '100',
            },
            width: {
                ...defaultTheme.width,
                '44': '11rem',
                '104': '26rem',
                '126': '29rem',
                '160': '40rem',
                '240': '60rem',
                '15/100': '15%',
                '46/100': '46%',
                '85/100': '85%',
                '86/100': '86%',
                '95/100': '95%',
                '98/100': '98%',
            },
            height: {
                ...defaultTheme.height,
                '120': '30rem',
            },
            fontFamily: {
                sans: ['Gilroy', 'sans-serif'],
            },
            colors: {
                gray: {
                    ...colors.gray,
                    100: '#F8F8F8',
                    125: '#EEF2F4',
                    150: '#E0E0E0',
                    200: '#BFBFBF',
                    400: '#8A8A8A',
                    500: '#888888',
                    600: '#434343',
                    800: '#211C1C',
                    900: '#252323',
                },
                orange: {
                    ...colors.orange,
                    400: '#FFBE17'
                },
                blue: {
                    ...colors.blue,
                    400: '#0C87CC'
                },
                red: {
                  ...colors.red,
                  300: '#EB3E1B',
                  400: '#EC3232',
                  500: '#AB002A',
                },
                green: {
                    ...colors.green,
                    700: '#159900',
                },
            }
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
}
