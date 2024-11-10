<?php

namespace App\Filament\Teacher\Resources\TeamResource\RelationManagers;

use App\Filament\Teacher\Resources\TeamResource\Pages\ViewTeam;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CompetitorProfilesRelationManager extends RelationManager
{
    protected static string $relationship = 'competitorProfiles';

    protected static ?string $title = 'Részletek';

    public static function canViewForRecord(
        Model  $ownerRecord,
        string $pageClass
    ): bool
    {
        return $pageClass == ViewTeam::class;
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Szerep')
                    ->badge(),
                Tables\Columns\TextColumn::make('name')->label('Név'),
                Tables\Columns\TextColumn::make('grade')
                    ->label('Évfolyam')
                    ->placeholder('Nem értelmezhető'),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail cím')
                    ->copyable()
                    ->placeholder('Nincs megadva'),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Felhasználónév')
                    ->copyable()
                    ->placeholder('Nem létezik a felhasználó'),
            ])
            ->defaultSort('type');
    }
}
