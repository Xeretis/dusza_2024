<?php

namespace App\Filament\Organizer\Widgets;

use App\Enums\TeamStatus;
use App\Filament\Organizer\Resources\TeamResource;
use App\Models\Team;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

class NonApprovedTeamsWidget extends BaseWidget
{
    public static ?int $sort = 2;
    protected static ?string $heading = 'Jóváhagyásra váró csapatok';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->recordUrl(fn(Team $record) => TeamResource::getUrl('view', ['record' => $record]))
            ->columns($this->getColumns())
            ->filters($this->getFilters());
    }

    protected function getQuery()
    {
        return Team::query()
            ->whereStatus(TeamStatus::SchoolApproved);
    }

    protected function getColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('category.name')
                ->label('Kategória')
                ->badge()
                ->sortable(),
            Tables\Columns\TextColumn::make('programmingLanguage.name')
                ->label('Programozási nyelv')
                ->formatStateUsing(fn($state) => $this->formatProgrammingLanguage($state))
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->label('Státusz')
                ->badge(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Létrehozva')
                ->dateTime()
                ->since()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Frissítve')
                ->dateTime()
                ->since()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    protected function formatProgrammingLanguage($state): HtmlString
    {
        $sanitized = str($state)->sanitizeHtml();
        return new HtmlString("<i>{$sanitized}</i>");
    }

    protected function getFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('category')
                ->label('Kategória')
                ->relationship('category', 'name'),
            Tables\Filters\SelectFilter::make('programmingLanguage')
                ->label('Programozási nyelv')
                ->relationship('programmingLanguage', 'name'),
        ];
    }
}
