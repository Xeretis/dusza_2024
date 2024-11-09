<x-filament-panels::page.simple>
    @if($userInvite == null)
        <p class="text-danger-600 dark:text-danger-400 text-center">Hibás jelentkezési link</p>
    @else
        {{ $this->form }}
    @endif
</x-filament-panels::page.simple>
