import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                'purple': 'rgb(123, 31, 162)',
                'violet': 'rgb(103, 58, 183)',
                'pink': 'rgb(244, 143, 177)',
            },
            backgroundSize: {
                '200%': '200%',
            },
            keyframes: {
                bgpankf: {
                    '0%': { backgroundPosition: '0% center' },
                    '100%': { backgroundPosition: '-200% center' },
                },
                scalekf: {
                    '0%': { transform: 'scale(0)' },
                    '50%': { transform: 'scale(1)' },
                    '100%': { transform: 'scale(0)' },
                },
                rotatekf: {
                    '0%': { transform: 'rotate(0deg)' },
                    '100%': { transform: 'rotate(180deg)' },
                }
            },
            animation: {
                bgpan: 'bgpankf 5s linear infinite',
                scale: 'scalekf 1500ms ease forwards',
                rotate: 'rotatekf 1000ms linear infinite',
            }
        },
    },
}
