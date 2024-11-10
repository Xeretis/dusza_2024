<?php

namespace App\Filament\Exports;

use App\Enums\CompetitorProfileType;
use App\Models\Team;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class TeamExporter extends Exporter
{
    protected static ?string $model = Team::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')->label('Név'),
            ExportColumn::make('category.name')->label('Kategória'),
            ExportColumn::make('programmingLanguage.name')->label('Programozási nyelv'),
            ExportColumn::make('school.name')->label('Iskola neve'),
            ExportColumn::make('school_address')->label('Iskola címe')->state(fn(Team $record) => self::getSchoolAddress($record)),
            ExportColumn::make('competitor1.name')->label('1. Csapattag neve')->state(fn(Team $record) => self::getCompetitorState($record, 0, 'name')),
            ExportColumn::make('competitor1.grade')->label('1. Csapattag évfolyama')->state(fn(Team $record) => self::getCompetitorState($record, 0, 'grade')),
            ExportColumn::make('competitor1.email')->label('1. Csapattag e-mail címe')->state(fn(Team $record) => self::getCompetitorState($record, 0, 'email', 'NINCS MEGADVA')),
            ExportColumn::make('competitor1.username')->label('1. Csapattag felhasználóneve')->state(fn(Team $record) => self::getCompetitorUsername($record, 0)),
            ExportColumn::make('competitor2.name')->label('2. Csapattag neve')->state(fn(Team $record) => self::getCompetitorState($record, 1, 'name')),
            ExportColumn::make('competitor2.grade')->label('2. Csapattag évfolyama')->state(fn(Team $record) => self::getCompetitorState($record, 1, 'grade')),
            ExportColumn::make('competitor2.email')->label('2. Csapattag e-mail címe')->state(fn(Team $record) => self::getCompetitorState($record, 1, 'email', 'NINCS MEGADVA')),
            ExportColumn::make('competitor2.username')->label('2. Csapattag felhasználóneve')->state(fn(Team $record) => self::getCompetitorUsername($record, 1)),
            ExportColumn::make('competitor3.name')->label('3. Csapattag neve')->state(fn(Team $record) => self::getCompetitorState($record, 2, 'name')),
            ExportColumn::make('competitor3.grade')->label('3. Csapattag évfolyama')->state(fn(Team $record) => self::getCompetitorState($record, 2, 'grade')),
            ExportColumn::make('competitor3.email')->label('3. Csapattag e-mail címe')->state(fn(Team $record) => self::getCompetitorState($record, 2, 'email', 'NINCS MEGADVA')),
            ExportColumn::make('competitor3.username')->label('3. Csapattag felhasználóneve')->state(fn(Team $record) => self::getCompetitorUsername($record, 2)),
            ExportColumn::make('substitute.name')->label('Póttag neve')->state(fn(Team $record) => self::getSubstituteState($record, 'name')),
            ExportColumn::make('substitute.grade')->label('Póttag évfolyama')->state(fn(Team $record) => self::getSubstituteState($record, 'grade')),
            ExportColumn::make('substitute.email')->label('Póttag e-mail címe')->state(fn(Team $record) => self::getSubstituteState($record, 'email', 'NINCS MEGADVA')),
            ExportColumn::make('substitute.username')->label('Póttag felhasználóneve')->state(fn(Team $record) => self::getSubstituteUsername($record)),
            ExportColumn::make('teacher_names')->label('Tanárok nevei')->state(fn(Team $record) => self::getTeacherNames($record)),
            ExportColumn::make('teacher_emails')->label('Tanárok e-mail címjei')->state(fn(Team $record) => self::getTeacherEmails($record)),
            ExportColumn::make('teachers')->label('Tanárok (json)')->state(fn(Team $record) => self::getTeachersJson($record))->enabledByDefault(false),
        ];
    }

    private static function getSchoolAddress(Team $record): string
    {
        return $record->school->zip . ' ' . $record->school->city . ' (' . $record->school->state . '), ' . $record->school->street;
    }

    private static function getCompetitorState(Team $record, int $index, string $attribute, string $default = 'NEM LÉTEZIK'): string
    {
        return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get($index, (object) [$attribute => $default])->$attribute;
    }

    private static function getCompetitorUsername(Team $record, int $index): string
    {
        return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get($index, (object) ['user' => ['username' => 'NEM LÉTEZIK']])->user?->username ?? 'NEM LÉTEZIK A FELHASZNÁLÓ';
    }

    private static function getSubstituteState(Team $record, string $attribute, string $default = 'NEM LÉTEZIK'): string
    {
        return $record->competitorProfiles->where('type', CompetitorProfileType::SubstituteStudent)->sortBy('id')->values()->get(0, (object) [$attribute => $default])->$attribute;
    }

    private static function getSubstituteUsername(Team $record): string
    {
        return $record->competitorProfiles->where('type', CompetitorProfileType::SubstituteStudent)->sortBy('id')->values()->get(0, (object) ['user' => ['username' => 'NEM LÉTEZIK']])->user?->username ?? 'NEM LÉTEZIK A FELHASZNÁLÓ';
    }

    private static function getTeacherNames(Team $record): string
    {
        return $record->competitorProfiles->where('type', CompetitorProfileType::Teacher)->sortBy('id')->values()->pluck('name')->join(', ');
    }

    private static function getTeacherEmails(Team $record): string
    {
        return $record->competitorProfiles->where('type', CompetitorProfileType::Teacher)->sortBy('id')->values()->pluck('email')->join(', ');
    }

    private static function getTeachersJson(Team $record): string
    {
        return $record->competitorProfiles->where('type', CompetitorProfileType::Teacher)->sortBy('id')->values()->select(['name', 'email'])->map(fn($t) => ['Név' => $t['name'], 'E-mail' => $t['email'] ?? 'NINCS MEGADVA'])->toJson(JSON_UNESCAPED_UNICODE);
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['category', 'programmingLanguage', 'school', 'competitorProfiles', 'competitorProfiles.user']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'A csapatok exportálsa befejeződött: ' . number_format($export->successful_rows) . ' sor sikeresen exportálva.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' sor sikertelenül exportálva.';
        }

        return $body;
    }
}
