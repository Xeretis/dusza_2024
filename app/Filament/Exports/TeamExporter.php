<?php

namespace App\Filament\Exports;

use App\Models\Team;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

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
                ->state(function (Team $record): float {
                    return $record->school->zip .
                        " " .
                        $record->school->city .
                        " (" .
                        $record->school->state .
                        "), " .
                        $record->school->street;
                })
                ->enabledByDefault(false),
        ];
    }

//    public static function modifyQuery(Builder $query): Builder
//    {
//        return $query->with([
//            'category', 'programmingLanguage', 'school'
//        ]);
//    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'A csapatok exportálsa befejeződött: ' . number_format($export->successful_rows) . ' ' . ' sor sikeresen exportálva.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . ' sor sikertelenül exportálva.';
        }

        return $body;
    }
}
