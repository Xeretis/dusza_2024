<?php

namespace App\Filament\Organizer\Resources\TeamResource\Pages;

use App\Enums\CompetitorProfileType;
use App\Filament\Organizer\Resources\TeamResource;
use App\Models\CompetitorProfile;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $members = CompetitorProfile::where('type', CompetitorProfileType::Student)
            ->whereHas('teams', function ($query) use ($data) {
                $query->where('teams.id', $data['id']);
            })
            ->take(3)
            ->get();

        if ($members->count() > 0) {
            $data['competitor1'] = [
                'id' => $members[0]->id,
                'name' => $members[0]->name,
                'grade' => $members[0]->grade,
                'email' => $members[0]->email,
                'invite' => false,
            ];
        }

        if ($members->count() > 1) {
            $data['competitor2'] = [
                'id' => $members[1]->id,
                'name' => $members[1]->name,
                'grade' => $members[1]->grade,
                'email' => $members[1]->email,
                'invite' => false,
            ];
        }

        if ($members->count() > 2) {
            $data['competitor3'] = [
                'id' => $members[2]->id,
                'name' => $members[2]->name,
                'grade' => $members[2]->grade,
                'email' => $members[2]->email,
                'invite' => false,
            ];
        }

        $substitute = CompetitorProfile::where('type', CompetitorProfileType::SubstituteStudent)
            ->whereHas('teams', function ($query) use ($data) {
                $query->where('teams.id', $data['id']);
            })
            ->first();

        if ($substitute != null) {
            $data['substitute'] = [
                'id' => $substitute->id,
                'name' => $substitute->name,
                'grade' => $substitute->grade,
                'email' => $substitute->email,
                'invite' => false,
            ];
        }

        $teachers = CompetitorProfile::where('type', CompetitorProfileType::Teacher)
            ->whereHas('teams', function ($query) use ($data) {
                $query->where('teams.id', $data['id']);
            })
            ->get()
            ->map(function ($p) {
                return ['id' => $p->id];
            })
            ->values();

        $data['teachers'] = $teachers->toArray();

        return $data;
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update(
            collect($data)
                ->forget([
                    'competitor1',
                    'competitor2',
                    'competitor3',
                    'substitute',
                    'teachers',
                ])
                ->toArray()
        );

        if (isset($data['teachers'])) {
            $record->teachers()->detach();
            $record->teachers()->sync(collect($data['teachers'])->map(fn($t) => $t['id'])->toArray());
        }

        $this->updateCompetitor($record, $data['competitor1']);
        $this->updateCompetitor($record, $data['competitor2']);
        $this->updateCompetitor($record, $data['competitor3']);
        $this->updateCompetitor($record, $data['substitute'], true);

        return $record;
    }

    protected function updateCompetitor(Model $record, array $competitorData, bool $isSubstitute = false): void
    {


        if ($competitorData['id'] == null && !empty($competitorData['name'])) {
            $userId = User::where('email', $competitorData['email'])->first()?->id;

            $competitorProfile = CompetitorProfile::create(
                collect($competitorData)
                    ->forget(['id', 'invite'])
                    ->merge([
                        'user_id' => $userId,
                        'type' => $isSubstitute ? CompetitorProfileType::SubstituteStudent : CompetitorProfileType::Student,
                    ])
                    ->toArray()
            );

            $competitorProfile->teams()->attach($record->id);
        } elseif (empty($competitorData['name'])) {
            CompetitorProfile::whereId($competitorData['id'])->delete();
        } else {
            $userId = User::where('email', $competitorData['email'])->first()?->id;

            $competitorProfile = CompetitorProfile::whereId($competitorData['id'])->first();

            $competitorProfile->update(
                collect($competitorData)
                    ->forget(['id'])
                    ->merge([
                        'user_id' => $userId
                    ])
                    ->toArray()
            );
            $competitorProfile->teams()->attach($record->id);
        }
    }

    protected function getHeaderActions(): array
    {
        return [Actions\ViewAction::make(), Actions\DeleteAction::make()];
    }
}
