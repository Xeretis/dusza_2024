<?php

namespace App\Filament\Teacher\Resources\TeamResource\Pages;

use App\Enums\CompetitorProfileType;
use App\Filament\Teacher\Resources\TeamResource;
use App\Models\CompetitorProfile;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->teams()
            ->where('teams.id', $parameters['record']->id)
            ->exists();
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->isUserPartOfTeam() ? null : TeamResource::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = $this->fillCompetitorsData($data);
        $data['teachers'] = $this->getTeachers($data['id']);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($this->filterData($data));

        if (isset($data['teachers'])) {
            $record->teachers()->sync($this->getTeacherIds($data['teachers']));
        }

        $this->updateCompetitors($record, $data);

        return $record;
    }

    protected function updateCompetitors(Model $record, array $data): void
    {
        $this->updateCompetitor($record, $data['competitor1']);
        $this->updateCompetitor($record, $data['competitor2']);
        $this->updateCompetitor($record, $data['competitor3']);
        $this->updateCompetitor($record, $data['substitute'], true);
    }

    protected function updateCompetitor(Model $record, array $competitorData, bool $isSubstitute = false): void
    {
        if ($this->isNewCompetitor($competitorData)) {
            $this->createCompetitorProfile($record, $competitorData, $isSubstitute);
        } elseif ($this->isEmptyCompetitor($competitorData)) {
            CompetitorProfile::whereId($competitorData['id'])->delete();
        } else {
            $this->updateExistingCompetitor($record, $competitorData);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    private function isUserPartOfTeam(): bool
    {
        return $this->getRecord()->refresh()->competitorProfiles()->where('user_id', auth()->id())->exists();
    }

    private function fillCompetitorsData(array $data): array
    {
        $members = $this->getCompetitors($data['id'], CompetitorProfileType::Student, 3);

        foreach ($members as $index => $member) {
            $data["competitor" . ($index + 1)] = $this->formatCompetitorData($member);
        }

        $substitute = $this->getCompetitors($data['id'], CompetitorProfileType::SubstituteStudent, 1)->first();
        if ($substitute) {
            $data['substitute'] = $this->formatCompetitorData($substitute);
        }

        return $data;
    }

    private function getCompetitors(int $teamId, CompetitorProfileType $type, int $limit)
    {
        return CompetitorProfile::where('type', $type)
            ->whereHas('teams', fn($query) => $query->where('teams.id', $teamId))
            ->take($limit)
            ->get();
    }

    private function formatCompetitorData($competitor): array
    {
        return [
            'id' => $competitor->id,
            'name' => $competitor->name,
            'grade' => $competitor->grade,
            'email' => $competitor->email,
            'invite' => false,
        ];
    }

    private function getTeachers(int $teamId): array
    {
        return CompetitorProfile::where('type', CompetitorProfileType::Teacher)
            ->whereHas('teams', fn($query) => $query->where('teams.id', $teamId))
            ->get()
            ->map(fn($p) => ['id' => $p->id])
            ->toArray();
    }

    private function filterData(array $data): array
    {
        return collect($data)->forget([
            'competitor1',
            'competitor2',
            'competitor3',
            'substitute',
            'teachers',
        ])->toArray();
    }

    private function getTeacherIds(array $teachers): array
    {
        return collect($teachers)->map(fn($t) => $t['id'])->toArray();
    }

    private function isNewCompetitor(array $competitorData): bool
    {
        return $competitorData['id'] == null && !empty($competitorData['name']);
    }

    private function isEmptyCompetitor(array $competitorData): bool
    {
        return empty($competitorData['name']);
    }

    private function createCompetitorProfile(Model $record, array $competitorData, bool $isSubstitute): void
    {
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
    }

    private function updateExistingCompetitor(Model $record, array $competitorData): void
    {
        $userId = User::where('email', $competitorData['email'])->first()?->id;

        $competitorProfile = CompetitorProfile::whereId($competitorData['id'])->first();

        $competitorProfile->update(
            collect($competitorData)
                ->forget(['id'])
                ->merge(['user_id' => $userId])
                ->toArray()
        );

        $competitorProfile->teams()->attach($record->id);
    }
}
