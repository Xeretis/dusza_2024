<?php

namespace App\Filament\Organizer\Resources;

use App\Enums\UserRole;
use App\Filament\Organizer\Resources\UserResource\Pages;
use App\Filament\Organizer\Resources\UserResource\RelationManagers;
use App\Filament\Organizer\Resources\UserResource\RelationManagers\AuditRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $label = 'felhasználó';

    protected static ?string $pluralLabel = 'felhasználók';

    protected static ?string $navigationGroup = 'Résztvevők';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('username')
                ->label('Felhasználónév')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->label('E-mail cím')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\DateTimePicker::make('email_verified_at')
                ->label('E-mail cím megerősítve')
                ->native(false),
            Forms\Components\TextInput::make('password')
                ->label('Jelszó')
                ->password()
                ->required(fn(string $operation) => $operation === 'create')
                ->dehydrated(fn($state) => filled($state))
                ->maxLength(255),
            Forms\Components\Select::make('role')
                ->label('Szerepkör')
                ->native(false)
                ->options(UserRole::class)
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
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Section::make([
                        TextEntry::make('username')->label('Felhasználónév'),
                        TextEntry::make('email')->label('E-mail cím'),
                        TextEntry::make('email_verified_at')
                            ->label('E-mail cím megerősítve')
                            ->placeholder('Nincs megerősítve')
                            ->dateTime()
                            ->placeholder('Not verified'),
                        TextEntry::make('role')
                            ->label('Szerepkör')
                            ->badge(),
                        TextEntry::make('school.name')
                            ->label('Iskola')
                            ->placeholder('Nem értelmezhető'),
                    ])
                        ->columns()
                        ->grow(),
                    Section::make([
                        TextEntry::make('created_at')
                            ->label('Létrehozva')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Frissítve')
                            ->dateTime(),
                    ])->grow(false),
                ])->from('md'),
            ])
            ->columns(false);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->label('Felhasználónév')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail cím')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('E-mail cím megerősítve')
                    ->placeholder('Nincs megerősítve')
                    ->dateTime()
                    ->since()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Szerepkör')
                    ->badge(),
                Tables\Columns\TextColumn::make('school.name')
                    ->label('Iskola')
                    ->placeholder('Nem értelmezhető')
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
                Tables\Filters\SelectFilter::make('school')
                    ->label('Iskola')
                    ->relationship('school', 'name'),
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('E-mail megerősítve')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull(
                            'email_verified_at'
                        ),
                        false: fn(Builder $query) => $query->whereNull(
                            'email_verified_at'
                        ),
                        blank: fn(Builder $query) => $query // In this example, we do not want to filter the query when it is blank.
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [AuditRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
