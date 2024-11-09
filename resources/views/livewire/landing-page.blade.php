<div class="overflow-x-hidden overflow-y-hidden m-0 light:bg-white dark:bg-black max-w-screen max-h-screen h-screen w-screen flex justify-center items-center">
    {{--    TODO: fix theme switcher--}}
    <div name="theme-switcher" class="fixed top-2 left-5 place-content-center">
        <x-theme-switcher size="lg"/>
    </div>
    <div >
        <div name="center" class="text-center translate-y-[-50px]">
            <p id="1" name="star" class="-z-10 absolute bottom-30 left-30 h-6 w-6 dark:blue-500 text-red-600"><x-heroicon-m-star /></p>

            <h1 class="light:text-black dark:text-white text-center px-4 sm:px-8 md:px-16 lg:px-32 xl:px-42 text-4xl sm:text-5xl md:text-6xl xl:text-7xl font-bold mb-20">
                Jelentkezés a <span class="font-bold align-center whitespace-nowrap bg-clip-text text-transparent bg-gradient-to-r from-red-500 via-purple via-violet via-pink to-red-500 bg-200% animate-bgpan">Dusza Versenyre</span>
            </h1>
            <div name="Links">
                <x-filament::button href="/" tag="a" color="gray">Bejelentkezés</x-filament::button>
    {{--                TODO: add a route and a documentation --}}
                <x-filament::button color="gray">Dokumentáció </x-filament::button>
            </div>
        </div>
    {{--        TODO: make stars rotate and maybe stars could also rotate around another point --}}
        <p id="2" name="star" class="-z-10 absolute top-60 left-50 h-6 w-6 dark:blue-500 text-red-600"><x-heroicon-m-star /></p>
        <p id="3" name="star" class="-z-10 absolute top-32 left-68 h-6 w-6 dark:blue-500 text-red-600"><x-heroicon-m-star /></p>
        <p id="4" name="star" class="-z-10 absolute left-4 top-44 h-6 w-6 dark:blue-500 text-red-600"><x-heroicon-m-star /></p>
        <p id="5" name="star" class="-z-10 absolute left-20 top-40 h-6 w-6 dark:blue-500 text-red-600"><x-heroicon-m-star /></p>
        <div class="fixed bottom-10 left-1/2 translate-x-[-50%]">
    {{--            TODO: add a route for an about us and actually make an about us page--}}
            <x-filament::button color="gray">A csapatról</x-filament::button>
        </div>
    </div>
</div>
@push('styles')
    <style>
        @keyframes background-pan {
            from {
                background-position: 0% center;
            }

            to {
                background-position: -200% center;
            }
        }
        @keyframes fade-out {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
        @keyframes scale {
            from, to {
                transform: scale(0);
            }
            50% {
                transform: scale(1);
                opacity: 1;
            }
            to {
                transform: scale(0);
                opacity: 0;
            }
        }
        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(180deg);
            }
        }
        p[name="star"] {
           animation: scale 1400ms ease infinite;
        }
        p > svg {
            animation: rotate 1000ms ease infinite;
        }
    </style>
@endpush
@push('scripts')
     <script>

         function Sleep(milliseconds) {
             return new Promise(resolve => setTimeout(resolve, milliseconds));
         }

         const rand = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

         const animate = star => {
            star.style.left = `${rand(20, 80)}%`;
            star.style.top = `${rand(20, 70)}%`;
            star.style.animation = "none";
            star.offsetHeight;
            star.style.animation = "";
         }

         const stars = document.getElementsByName('star');
         const interval = 2000;

         setInterval(() => {
             for (const star of stars) {
                    animate(star);
             }
             Sleep(interval);
             Sleep(interval);
         }, interval/2);
     </script>
@endpush

