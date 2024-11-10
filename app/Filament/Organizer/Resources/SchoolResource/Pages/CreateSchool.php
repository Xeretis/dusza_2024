<?php

namespace App\Filament\Organizer\Resources\SchoolResource\Pages;

use App\Enums\UserRole;
use App\Filament\Organizer\Resources\SchoolResource;
use App\Models\UserInvite;
use App\Notifications\UserInviteNotification;
use DragonCode\Support\Facades\Helpers\Str;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Throwable;

class CreateSchool extends CreateRecord
{
    protected static string $resource = SchoolResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            DB::beginTransaction();

            $school = static::getModel()::create(collect($data)->forget('invite')->toArray());

            if ($data['invite']) {
                $inv = UserInvite::create([
                    'role' => UserRole::SchoolManager,
                    'email' => $school->contact_email,
                    'token' => Str::random(64),
                    'school_id' => $school->id,
                ]);

                Notification::route(
                    'mail',
                    $school->contact_email
                )->notify(new UserInviteNotification($inv->token));
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $school;
    }

}
