<div class="absolute top-2 left-2 z-40 ">
    <x-filament::button
        x-on:click="console.log('clicked')"
        x-data="theme: 'light'"
        x-effect="localStorage.getItem('theme') = theme"
        color="gray"
        id="themeSwitcher"
        size="lg"
    >
        <x-heroicon-m-moon class="h-6 w-6" />
    </x-filament::button>
</div>
@push('scripts')
    <script>
        // TODO: make this actually work
        const themeSwitcher = document.getElementById('themeSwitcher');

        themeSwitcher.addEventListener('click', () => {
            const mode = localStorage.getItem('theme');
            if (mode === 'dark') {
                localStorage.setItem('theme', 'light');
            } else if (mode === 'light') {
                localStorage.setItem('theme', 'system');
            } else {
                localStorage.setItem('theme', 'dark');
            }

            setTheme();
        });

        function setTheme() {
            const themeSwitcher = document.getElementById('themeSwitcher');
            console.log("i want to kill myself");
            const mode = localStorage.getItem('theme');
            if (mode === 'dark') {
                themeSwitcher.innerHTML = '<x-heroicon-m-sun class="h-6 w-6" />';
                document.documentElement.classList.add('dark')
            } else if (mode === 'light') {
                themeSwitcher.innerHTML = '<x-heroicon-m-computer-desktop class="h-6 w-6" />';
                document.documentElement.classList.remove('dark')
            } else {
                themeSwitcher.innerHTML = '<x-heroicon-m-moon class="h-6 w-6" />';
                const prefersDark = window.matchMedia("(prefers-color-scheme: dark)")
                if (prefersDark.matches) {
                    document.documentElement.classList.add('dark')
                } else {
                    document.documentElement.classList.remove('dark')
                }
            }
        }
    </script>

@endpush
