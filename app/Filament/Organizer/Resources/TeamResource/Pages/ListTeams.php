<?php

namespace App\Filament\Organizer\Resources\TeamResource\Pages;

use App\Filament\Exports\TeamExporter;
use App\Filament\Organizer\Resources\TeamResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\ExportColumn;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\ExportAction as ExportTableAction;
use Filament\Tables\Actions\ExportBulkAction as ExportTableBulkAction;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->exporter(TeamExporter::class)
                ->form(fn(ExportAction|ExportTableAction|ExportTableBulkAction $action): array => [
                    ...($action->hasColumnMapping() ? [Fieldset::make(__('filament-actions::export.modal.form.columns.label'))
                        ->columns(3)
                        ->inlineLabel()
                        ->schema(function () use ($action): array {
                            return array_map(
                                fn(ExportColumn $column): Split => Split::make([
                                    Checkbox::make('isEnabled')
                                        ->label(__('filament-actions::export.modal.form.columns.form.is_enabled.label', ['column' => $column->getName()]))
                                        ->hiddenLabel()
                                        ->default($column->isEnabledByDefault())
                                        ->live()
                                        ->grow(false),
                                    TextInput::make('label')
                                        ->label(__('filament-actions::export.modal.form.columns.form.label.label', ['column' => $column->getName()]))
                                        ->hiddenLabel()
                                        ->default($column->getLabel())
                                        ->placeholder($column->getLabel())
                                        ->disabled(fn(Get $get): bool => !$get('isEnabled'))
                                ])
                                    ->verticallyAlignCenter()
                                    ->statePath($column->getName()),
                                $action->getExporter()::getColumns(),
                            );
                        })
                        ->statePath('columnMap')] : []),
                    ...$action->getExporter()::getOptionsFormComponents(),
                ])
                ->modalWidth(MaxWidth::ScreenExtraLarge),
            Actions\CreateAction::make(),
        ];
    }
}
