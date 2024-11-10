<?php

namespace App\Filament\Organizer\Resources;

use App\Filament\Organizer\Resources\UserInviteResource\Pages;
use App\Filament\Organizer\Resources\UserInviteResource\RelationManagers;
use App\Models\UserInvite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserInviteResource extends Resource
{
    protected static ?string $model = UserInvite::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $label = 'meghívó';

    protected static ?string $pluralLabel = 'meghívók';

    protected static ?string $navigationGroup = 'Résztvevők';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            //
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail cím')
                    ->searchable(),
                Tables\Columns\TextColumn::make('competitorProfile.name')
                    ->label('Meghívott neve')
                    ->placeholder('Nem értelmezhető')
                    ->searchable(),

                Tables\Columns\TextColumn::make('school.name')
                    ->label('Iskola')
                    ->searchable()
                    ->placeholder('Nem értelmezhető'),
                Tables\Columns\TextColumn::make('role')
                    ->label('Szerepkör')
                    ->badge(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Lejár')
                    ->dateTime()
                    ->since()
                    ->placeholder('Soha')
                    ->sortable(),
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
            ->filters([])
            ->actions([Tables\Actions\DeleteAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserInvites::route('/'),
        ];
    }
}
