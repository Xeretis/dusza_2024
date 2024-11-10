<?php

namespace App\Filament\Imports;

use App\Models\ProgrammingLanguage;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProgrammingLanguageImporter extends Importer
{
    protected static ?string $model = ProgrammingLanguage::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Név')
                ->example('C++')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'A programozási nyelvek importálása befejeződött: ' . number_format($import->successful_rows) . ' sor sikeresen importálva.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' sor importálása sikertelen.';
        }

        return $body;
    }

    public function resolveRecord(): ?ProgrammingLanguage
    {
        return new ProgrammingLanguage();
    }
}
