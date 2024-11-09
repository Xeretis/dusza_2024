import {defineConfig} from 'vite';
import laravel, {refreshPaths} from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/common/theme.css', 'resources/css/filament/competitor/theme.css', 'resources/css/filament/organizer/theme.css', 'resources/css/filament/school-manager/theme.css', 'resources/css/filament/teacher/theme.css'],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
                'app/Filament/**',
                'app/Providers/Filament/**',
                'resources/css/filament/common/theme.css',
                'resources/views/**',
            ],
        }),
    ],
});
