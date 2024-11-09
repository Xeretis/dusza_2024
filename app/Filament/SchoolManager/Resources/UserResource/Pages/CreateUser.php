<?php

namespace App\Filament\SchoolManager\Resources\UserResource\Pages;

use App\Filament\SchoolManager\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
