<?php

namespace App\Filament\Organizer\Resources;

use App\Enums\UserRole;
use App\Enums\UserRoleInvite;
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
            Forms\Components\Select::make('role')
                ->label('Szerepkör')
                ->native(false)
                ->options(UserRoleInvite::class)
                ->selectablePlaceholder(false)
                ->required()
                ->afterStateUpdated(function ($state, Forms\Set $set) {
                    if ($state == UserRole::Organizer->value) {
                        $set('school_id', null);
                    }
                })
                ->live(),
            Forms\Components\Select::make('school_id')
                ->label('Iskola')
                ->relationship('school', 'name')
                ->disabled(
                    fn(Forms\Get $get) => $get('role') ==
                        UserRole::Organizer->value
                )
                ->native(false)
                ->selectablePlaceholder(false)
                ->required(
                    fn(Forms\Get $get) => $get('role') !=
                        UserRole::Organizer->value
                ),
            Forms\Components\TextInput::make('email')
                ->label('E-mail cím')
                ->email()
                ->required()
                ->maxLength(255),
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
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Szerepkör')
                    ->options(UserRole::class),
            ])
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
            'create' => Pages\CreateUserInvite::route('/create'),
        ];
    }
}
