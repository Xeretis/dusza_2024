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
            ExportColumn::make('name')
                ->label('Név'),
            ExportColumn::make('category.name')
                ->label('Kategória'),
            ExportColumn::make('programmingLanguage.name')
                ->label('Programozási nyelv'),
            ExportColumn::make('school.name')
                ->label('Iskola neve'),
            ExportColumn::make('school_address')
                ->label('Iskola címe')
                ->state(function (Team $record) {
                    return $record->school->zip .
                        " " .
                        $record->school->city .
                        " (" .
                        $record->school->state .
                        "), " .
                        $record->school->street;
                }),
            
            ExportColumn::make('competitor1.name')
                ->label('1. Csapattag neve')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(0, (object)['name' => 'NEM LÉTEZIK'])->name;
                }),

            ExportColumn::make('competitor1.grade')
                ->label('1. Csapattag évfolyama')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(0, (object)['grade' => 'NEM LÉTEZIK'])->grade;
                }),

            ExportColumn::make('competitor1.email')
                ->label('1. Csapattag e-mail címe')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(0, (object)['email' => 'NEM LÉTEZIK'])->email ?? 'NINCS MEGADVA';
                }),

            ExportColumn::make('competitor1.username')
                ->label('1. Csapattag felhasználóneve')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(0, (object)['user' => ['username' => 'NEM LÉTEZIK']])->user?->username ?? 'NEM LÉTEZIK A FELHASZNÁLÓ';
                }),

            ExportColumn::make('competitor2.name')
                ->label('2. Csapattag neve')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(1, (object)['name' => 'NEM LÉTEZIK'])->name;
                }),

            ExportColumn::make('competitor2.grade')
                ->label('2. Csapattag évfolyama')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(1, (object)['grade' => 'NEM LÉTEZIK'])->grade;
                }),

            ExportColumn::make('competitor2.email')
                ->label('2. Csapattag e-mail címe')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(1, (object)['email' => 'NEM LÉTEZIK'])->email ?? 'NINCS MEGADVA';
                }),

            ExportColumn::make('competitor2.username')
                ->label('2. Csapattag felhasználóneve')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(1, (object)['user' => ['username' => 'NEM LÉTEZIK']])->user?->username ?? 'NEM LÉTEZIK A FELHASZNÁLÓ';
                }),

            ExportColumn::make('competitor3.name')
                ->label('3. Csapattag neve')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(2, (object)['name' => 'NEM LÉTEZIK'])->name;
                }),

            ExportColumn::make('competitor3.grade')
                ->label('3. Csapattag évfolyama')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(2, (object)['grade' => 'NEM LÉTEZIK'])->grade;
                }),

            ExportColumn::make('competitor3.email')
                ->label('3. Csapattag e-mail címe')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(2, (object)['email' => 'NEM LÉTEZIK'])->email ?? 'NINCS MEGADVA';
                }),

            ExportColumn::make('competitor3.username')
                ->label('3. Csapattag felhasználóneve')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Student)->sortBy('id')->values()->get(2, (object)['user' => ['username' => 'NEM LÉTEZIK']])->user?->username ?? 'NEM LÉTEZIK A FELHASZNÁLÓ';
                }),

            ExportColumn::make('substitute.name')
                ->label('Póttag neve')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::SubstituteStudent)->sortBy('id')->values()->get(0, (object)['name' => 'NEM LÉTEZIK'])->name;
                }),

            ExportColumn::make('substitute.grade')
                ->label('Póttag évfolyama')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::SubstituteStudent)->sortBy('id')->values()->get(0, (object)['grade' => 'NEM LÉTEZIK'])->grade;
                }),

            ExportColumn::make('substitute.email')
                ->label('Póttag e-mail címe')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::SubstituteStudent)->sortBy('id')->values()->get(0, (object)['email' => 'NEM LÉTEZIK'])->email ?? 'NINCS MEGADVA';
                }),

            ExportColumn::make('substitute.username')
                ->label('Póttag felhasználóneve')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::SubstituteStudent)->sortBy('id')->values()->get(0, (object)['user' => ['username' => 'NEM LÉTEZIK']])->user?->username ?? 'NEM LÉTEZIK A FELHASZNÁLÓ';
                }),

            ExportColumn::make('teachers')
                ->label('Tanárok (json)')
                ->state(function (Team $record) {
                    return $record->competitorProfiles->where('type', CompetitorProfileType::Teacher)->sortBy('id')->values()->select(['name', 'email'])->map(fn($t) => ['Név' => $t['name'], 'E-mail' => $t['email'] ?? 'NINCS MEGADVA'])->toJson();
                })
        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with([
            'category', 'programmingLanguage', 'school', 'competitorProfiles', 'competitorProfiles.user'
        ]);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'A csapatok exportálsa befejeződött: ' . number_format($export->successful_rows) . ' ' . ' sor sikeresen exportálva.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . ' sor sikertelenül exportálva.';
        }

        return $body;
    }
}
