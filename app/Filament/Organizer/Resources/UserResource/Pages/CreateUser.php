<?php

namespace App\Filament\Organizer\Resources\UserResource\Pages;

use App\Filament\Organizer\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
