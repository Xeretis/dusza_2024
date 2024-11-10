<?php

namespace App\Filament\Organizer\Resources\TeamResource\Pages;

use App\Enums\CompetitorProfileType;
use App\Enums\UserRole;
use App\Filament\Organizer\Resources\TeamResource;
use App\Models\CompetitorProfile;
use App\Models\User;
use App\Models\UserInvite;
use App\Notifications\UserInviteNotification;
use DragonCode\Support\Facades\Helpers\Str;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $model = static::getModel()::create([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'programming_language_id' => $data['programming_language_id'],
            'school_id' => $data['school_id'],
        ]);

        $this->createCompetitorProfile($data['competitor1'], $model);
        $this->createCompetitorProfile($data['competitor2'], $model);
        $this->createCompetitorProfile($data['competitor3'], $model);
        $this->createCompetitorProfile($data['substitute'], $model, true);

        if (!empty($data['teachers'])) {
            foreach ($data['teachers'] as ['id' => $id]) {
                $model->competitorProfiles()->attach($id);
            }
        }

        return $model;
    }

    private function createCompetitorProfile(array $competitorData, Model $teamModel, bool $isSubstitute = false): void
    {
        if (!empty($competitorData['name'])) {
            $userId = User::where('email', $competitorData['email'])->first()?->id;

            DB::beginTransaction();

            $competitorProfile = CompetitorProfile::create(
                collect($competitorData)
                    ->forget(['id', 'invite'])
                    ->merge(['user_id' => $userId, 'type' => $isSubstitute ? CompetitorProfileType::SubstituteStudent : CompetitorProfileType::Student])
                    ->toArray()
            );

            $competitorProfile->teams()->attach($teamModel);

            if ($competitorData['invite']) {
                $inv = UserInvite::create([
                    'role' => UserRole::Teacher,
                    'email' => $competitorProfile['email'],
                    'token' => Str::random(64),
                    'competitor_profile_id' => $competitorProfile->id
                ]);

                Notification::route('mail', $competitorProfile['email'])
                    ->notify(new UserInviteNotification($inv->token));
            }

            DB::commit();
        }
    }
}
