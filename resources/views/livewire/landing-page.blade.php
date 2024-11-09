<div class="overflow-x-hidden overflow-y-hidden m-0 light:bg-white dark:bg-black h-screen w-screen flex justify-center items-center">
    {{--    TODO: fix theme switcher--}}
    <div name="theme-switcher" class="fixed top-2 left-5 place-content-center">
        <x-theme-switcher size="lg"/>
    </div>
    <div class="">
        <div name="center" class="text-center translate-y-[-50px]">
            <h1 class="light:text-black dark:text-white text-center px-4 sm:px-8 md:px-16 lg:px-32 xl:px-42 text-4xl sm:text-5xl md:text-6xl xl:text-7xl font-bold mb-20">
                Jelentkezés a <span class="font-bold align-center whitespace-nowrap bg-clip-text text-transparent bg-gradient-to-r from-red-500 via-purple via-violet via-pink to-red-500 bg-200% animate-bgpan">Dusza</span> Versenyre
            </h1>
            <div name="Links">
                <x-filament::button href="/" tag="a" color="gray">Bejelentkezés</x-filament::button>
    {{--                TODO: add a route and a documentation --}}
                <x-filament::button color="gray">Dokumentáció </x-filament::button>
            </div>
        </div>
    {{--        TODO: make stars rotate and maybe stars could also rotate around another point --}}
            <p class="animate-scale animate-rotate absolute top-30 left-30 h-6 w-6 text-red-600">{{ svg('heroicon-m-star') }}</p>
        <div class="fixed bottom-10 left-1/2 translate-x-[-50%]">
    {{--            TODO: add a route for an about us and actually make an about us page--}}
            <x-filament::button color="gray">A csapatról</x-filament::button>
        </div>
    </div>
</div>
@push('scipts')
    {{--    TODO: implement stars popping up on screen --}}
@endpush

