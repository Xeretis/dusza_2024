<x-filament-breezy::grid-section md=2 title="Személyes adatok" description="Személyes adatok kezelése">
    <x-filament::card>
        @if($competitorProfile != null)
            <form wire:submit.prevent="submit" class="space-y-6">

                {{ $this->form }}

                <div class="text-right">
                    <x-filament::button type="submit" form="submit" class="align-right">
                        Mentés
                    </x-filament::button>
                </div>
            </form>
        @else
            <p class="text-sm">Hozz létre egy csapatot, hogy elérhetővé válljon ez a szekció!</p>
        @endif
    </x-filament::card>
</x-filament-breezy::grid-section>
