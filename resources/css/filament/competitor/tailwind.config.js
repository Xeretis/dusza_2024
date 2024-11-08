import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Competitor/**/*.php',
        './resources/views/filament/competitor/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
