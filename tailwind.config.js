import preset from './vendor/filament/support/tailwind.config.preset';

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/jaocero/activity-timeline/resources/views/**/*.blade.php',
        './vendor/codewithdennis/filament-simple-alert/resources/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                purple: 'rgb(123, 31, 162)',
                violet: 'rgb(103, 58, 183)',
                pink: 'rgb(244, 143, 177)',
            },
            backgroundSize: {
                '200%': '200%',
            },
            keyframes: {
                bgpankf: {
                    '0%': { backgroundPosition: '0% center' },
                    '100%': { backgroundPosition: '-200% center' },
                },
            },
            animation: {
                bgpan: 'bgpankf 5s linear infinite',
            },
        },
    },
};
