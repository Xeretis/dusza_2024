<?php

namespace App\Filament\Teacher\Resources\TeamResource\Pages;

use App\Enums\CompetitorProfileType;
use App\Enums\UserRole;
use App\Filament\Teacher\Resources\TeamResource;
use App\Models\CompetitorProfile;
use App\Models\User;
use App\Models\UserInvite;
use App\Notifications\UserInviteNotification;
use App\Settings\CompetitionSettings;
use DragonCode\Support\Facades\Helpers\Str;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        if (auth()->guest())
            return false;

        $competitionSettings = app(CompetitionSettings::class);
        $canCreate = $competitionSettings->registration_deadline->isFuture() && $competitionSettings->registration_cancelled_at == null;
        return CompetitorProfile::where('user_id', auth()->id())->exists() && $canCreate;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $this->ensureTeacherIsIncluded($data);

        $model = static::getModel()::create([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'programming_language_id' => $data['programming_language_id'],
            'school_id' => $data['school_id'],
        ]);

        $this->createCompetitorProfiles($data, $model);

        if (!empty($data['teachers'])) {
            foreach ($data['teachers'] as ['id' => $id]) {
                $model->competitorProfiles()->attach($id);
            }
        }

        return $model;
    }

    private function ensureTeacherIsIncluded(array &$data): void
    {
        $filteredTeachers = array_filter($data['teachers'], function ($item) {
            return isset($item['id']) && $item['id'] === auth()->user()->competitorProfile->id;
        });

        if (count($filteredTeachers) !== 1) {
            $data['teachers'][] = [
                'id' => auth()->user()->competitorProfile->id
            ];
        }
    }

    private function createCompetitorProfiles(array $data, Model $model): void
    {
        $this->createCompetitorProfile($data['competitor1'], $model);
        $this->createCompetitorProfile($data['competitor2'], $model);
        $this->createCompetitorProfile($data['competitor3'], $model);
        $this->createCompetitorProfile($data['substitute'], $model, true);
    }

    private function createCompetitorProfile(
        array $competitorData,
        Model $teamModel,
        bool  $isSubstitute = false
    ): void
    {
        if (!empty($competitorData['name'])) {
            $userId = User::where('email', $competitorData['email'])->first()?->id;

            try {
                DB::beginTransaction();

                $competitorProfile = CompetitorProfile::create(
                    collect($competitorData)
                        ->forget(['id', 'invite'])
                        ->merge([
                            'user_id' => $userId,
                            'type' => $isSubstitute
                                ? CompetitorProfileType::SubstituteStudent
                                : CompetitorProfileType::Student,
                        ])
                        ->toArray()
                );

                $competitorProfile->teams()->attach($teamModel);

                if ($competitorData['invite'] ?? false) {
                    $this->sendInvite($competitorProfile);
                }

                DB::commit();
            } catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    private function sendInvite(CompetitorProfile $competitorProfile): void
    {
        $inv = UserInvite::create([
            'role' => UserRole::Teacher,
            'email' => $competitorProfile['email'],
            'token' => Str::random(64),
            'competitor_profile_id' => $competitorProfile->id,
        ]);

        Notification::route('mail', $competitorProfile['email'])
            ->notify(new UserInviteNotification($inv->token));
    }
}
