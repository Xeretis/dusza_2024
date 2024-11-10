<x-filament-panels::page.simple>
    @if(!$canCreate)
        <p class="text-danger-600 dark:text-danger-400 text-center">Lejárt a jelentkezési időszak!</p>
    @else
        <x-filament-panels::form id="form" wire:submit="create">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
            />
        </x-filament-panels::form>
    @endif
</x-filament-panels::page.simple>
