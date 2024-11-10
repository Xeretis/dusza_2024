<?php

namespace App\Filament\SchoolManager\Widgets;

use App\Enums\TeamStatus;
use App\Models\Team;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

class NonApprovedTeamsWidget extends BaseWidget
{
    public static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Jóváhagyásra váró csapatok';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Team::query()
                    ->whereStatus(TeamStatus::Inactive)
                    ->whereSchoolId(auth()->user()->school_id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategória')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('programmingLanguage.name')
                    ->label('Programozási nyelv')
                    ->formatStateUsing(function ($state) {
                        $sanitized = str($state)->sanitizeHtml();
                        return new HtmlString("<i>{$sanitized}</i>");
                    })
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
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategória')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('programmingLanguage')
                    ->label('Programozási nyelv')
                    ->relationship('programmingLanguage', 'name'),
            ]);
    }
}
