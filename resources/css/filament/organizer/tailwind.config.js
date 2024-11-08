import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Organizer/**/*.php',
        './resources/views/filament/organizer/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
