<?php

namespace App\Filament\SchoolManager\Resources;

use App\Enums\UserRole;
use App\Filament\SchoolManager\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
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
    protected static ?string $navigationGroup = 'Iskolai';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Add form schema here
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumns())
            ->filters(self::getTableFilters())
            ->modifyQueryUsing(fn(Builder $query) => self::modifyTableQuery($query));
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('username')
                ->label('Felhasználónév')
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->label('E-mail cím')
                ->searchable(),
            Tables\Columns\TextColumn::make('role')
                ->label('Szerepkör')
                ->badge(),
            Tables\Columns\TextColumn::make('email_verified_at')
                ->label('E-mail cím megerősítve')
                ->placeholder('Nincs megerősítve')
                ->dateTime()
                ->since()
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
        ];
    }

    protected static function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('role')
                ->label('Szerepkör')
                ->options(UserRole::class),
            Tables\Filters\TernaryFilter::make('email_verified_at')
                ->label('E-mail megerősítve')
                ->queries(
                    true: fn(Builder $query) => $query->whereNotNull('email_verified_at'),
                    false: fn(Builder $query) => $query->whereNull('email_verified_at'),
                    blank: fn(Builder $query) => $query
                ),
        ];
    }

    protected static function modifyTableQuery(Builder $query): Builder
    {
        return $query
            ->whereSchoolId(auth()->user()->school_id)
            ->whereRole(UserRole::SchoolManager);
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}
