import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/SchoolManager/**/*.php',
        './resources/views/filament/school-manager/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
