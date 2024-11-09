<?php

namespace App\Filament\Organizer\Resources\UserResource\Pages;

use App\Filament\Organizer\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use STS\FilamentImpersonate\Pages\Actions\Impersonate;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Impersonate::make()
                ->label("Megszemélyesítés")
                ->redirectTo(route("filament.common.home")),
            Actions\EditAction::make(),
        ];
    }
}
