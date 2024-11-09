<div class="absolute top-2 left-2 z-40">
    <x-filament::button
        color="gray"
        id="themeSwitcher"
        size="lg"
        x-data="{ theme: localStorage.getItem('theme') || 'system' }"
        x-on:click="theme = theme === 'dark' ? 'light' : (theme === 'light' ? 'system' : 'dark'); localStorage.setItem('theme', theme); setTheme(theme);"
    >
        <x-heroicon-m-moon class="h-6 w-6" x-show="theme === 'dark'" />
        <x-heroicon-m-sun class="h-6 w-6" x-show="theme === 'light'" />
        <x-heroicon-m-computer-desktop class="h-6 w-6" x-show="theme === 'system'" />
    </x-filament::button>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTheme(localStorage.getItem('theme') || 'system');
        });

        function setTheme(theme) {
            const htmlElement = document.documentElement;
            if (theme === 'dark') {
                htmlElement.classList.add('dark');
            } else if (theme === 'light') {
                htmlElement.classList.remove('dark');
            } else {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (prefersDark) {
                    htmlElement.classList.add('dark');
                } else {
                    htmlElement.classList.remove('dark');
                }
            }
        }
    </script>
@endpush
