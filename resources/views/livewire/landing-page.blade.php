<div
    class="bg-gray-50 dark:bg-gray-950 max-w-screen max-h-screen h-screen w-screen relative">
    <div name="theme-switcher" class="fixed top-2 right-2 place-content-center z-50">
        <x-theme-switcher/>
    </div>
    <div class="constellations absolute inset-0 *:z-10">
        <p id="1" name="star" class="absolute h-6 w-6 text-blue-900">
            <x-heroicon-m-star/>
        </p>
        <p id="2" name="star" class="absolute h-6 w-6 text-blue-900">
            <x-heroicon-m-star/>
        </p>
        <p id="3" name="star" class="absolute h-6 w-6 text-blue-900">
            <x-heroicon-m-star/>
        </p>
        <p id="4" name="star" class="absolute h-6 w-6 text-blue-900">
            <x-heroicon-m-star/>
        </p>
        <p id="5" name="star" class="absolute h-6 w-6 text-blue-900">
            <x-heroicon-m-star/>
        </p>
        <p id="6" name="star" class="absolute h-6 w-6 text-blue-900">
            <x-heroicon-m-star/>
        </p>
        <p id="7" name="star" class="absolute h-6 w-6 text-blue-900">
            <x-heroicon-m-star/>
        </p>
        <p id="8" name="star" class="absolute h-6 w-6 text-blue-900">
            <x-heroicon-m-star/>
        </p>
        <svg id="lines" class="absolute top-0 left-0 w-full h-full z-10"></svg>
    </div>
    <div class="flex justify-center items-center max-w-screen max-h-screen h-screen w-screen absolute inset-0 z-40">
        <div name="center" class="text-center">
            <h1 class="z-20 text-black dark:text-white sm:px-8 md:px-16 lg:px-32 xl:px-42 text-4xl sm:text-5xl md:text-6xl xl:text-7xl font-bold mb-20">
                Jelentkezés a <span
                    class="z-20 font-bold whitespace-nowrap bg-clip-text text-transparent bg-gradient-to-r from-blue-800 via-purple to-blue-800 bg-200% animate-bgpan">Dusza Versenyre</span>
            </h1>
            <div name="Links">
                <x-filament::button class="z-20 mr-1" href="/login" tag="a" color="gray">Bejelentkezés</x-filament::button>
                <x-filament::button class="z-20" href="/kb/prologue/getting-started" tag="a" color="gray">Dokumentáció
                </x-filament::button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        @keyframes background-pan {
            from {
                background-position: 0 center;
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
                opacity: 0.7;
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
                transform: rotate(270deg);
            }
        }

        p[name="star"] {
            animation: scale 2800ms ease infinite;
        }

        p > svg {
            animation: rotate 2000ms ease infinite;
        }

        line {
            animation: fade-out 2200ms ease-in infinite;
        }
    </style>
@endpush
@push('scripts')
    <script>
        function Sleep(milliseconds) {
            return new Promise(resolve => setTimeout(resolve, milliseconds));
        }

        const rand = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

        const getDistance = (star1, star2) => {
            const x1 = star1.offsetLeft + star1.offsetWidth / 2;
            const y1 = star1.offsetTop + star1.offsetHeight / 2;
            const x2 = star2.offsetLeft + star2.offsetWidth / 2;
            const y2 = star2.offsetTop + star2.offsetHeight / 2;
            return Math.sqrt((x1 - x2) ** 2 + (y1 - y2) ** 2);
        };

        const animate = star => {
            star.style.left = `${rand(20, 80)}%`;
            star.style.top = `${rand(20, 70)}%`;
            star.style.animation = "none";
            star.offsetHeight;
            star.style.animation = "";
        };

        const updateLines = () => {
            const stars = document.getElementsByName('star');
            const lines = document.getElementById('lines');
            lines.innerHTML = '';

            const connections = new Map();

            for (let i = 0; i < stars.length; i++) {
                connections.set(i, new Set());
            }

            for (let i = 0; i < stars.length; i++) {
                if (i === 7) continue;

                const distances = [];

                for (let j = 0; j < stars.length; j++) {
                    if (i !== j) {
                        const distance = getDistance(stars[i], stars[j]);
                        distances.push({index: j, distance});
                    }
                }

                distances.sort((a, b) => a.distance - b.distance);

                let maxConnections = 2;
                if (i === 0) maxConnections = 1;
                if (i === 3) maxConnections = 3;

                for (let k = 0; k < maxConnections; k++) {
                    const closestStarIndex = distances[k].index;
                    if (connections.get(i).size < maxConnections && connections.get(closestStarIndex).size < 2) {
                        connections.get(i).add(closestStarIndex);
                        connections.get(closestStarIndex).add(i);
                    }
                }
            }

            connections.forEach((connectedStars, i) => {
                connectedStars.forEach(j => {
                    const x1 = stars[i].offsetLeft + stars[i].offsetWidth / 2;
                    const y1 = stars[i].offsetTop + stars[i].offsetHeight / 2;
                    const x2 = stars[j].offsetLeft + stars[j].offsetWidth / 2;
                    const y2 = stars[j].offsetTop + stars[j].offsetHeight / 2;

                    const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                    line.setAttribute('x1', x1);
                    line.setAttribute('y1', y1);
                    line.setAttribute('x2', x2);
                    line.setAttribute('y2', y2);
                    line.setAttribute('stroke', 'blue');
                    line.setAttribute('stroke-opacity', '0.2');
                    line.setAttribute('stroke-width', '2');
                    lines.appendChild(line);
                });
            });
        };

        const stars = document.getElementsByName('star');
        const interval = 4000;

        setInterval(async () => {
            for (const star of stars) {
                animate(star);
            }
            await Sleep(100);
            updateLines();
            await Sleep(interval * 2);
        }, interval / 2);

        const bigDipperCoordinates = [
            {left: '6%', top: '40%'}, // Star 1
            {left: '26%', top: '25%'}, // Star 2
            {left: '42%', top: '367px'}, // Star 3
            {left: '61%', top: '39%'}, // Star 4
            {left: '91%', top: '32%'}, // Star 5
            {left: '92%', top: '69%'}, // Star 6
            {left: '68%', top: '73%'},  // Star 7
            {left: '21%', top: '94%'}  // Star 8

        ];

        const setBigDipper = () => {
            const stars = document.getElementsByName('star');
            bigDipperCoordinates.forEach((coord, index) => {
                stars[index].style.left = coord.left;
                stars[index].style.top = coord.top;
            });
        };

        window.onload = () => {
            setBigDipper();
            updateLines();
        };

    </script>
@endpush

