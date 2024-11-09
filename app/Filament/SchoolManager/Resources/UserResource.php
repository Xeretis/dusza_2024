<?php

namespace App\Filament\SchoolManager\Resources;

use App\Enums\UserRole;
use App\Filament\SchoolManager\Resources\UserResource\Pages;
use App\Filament\SchoolManager\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = "heroicon-o-users";

    protected static ?string $label = "felhasználó";

    protected static ?string $pluralLabel = "felhasználók";

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
                Tables\Columns\TextColumn::make("username")
                    ->label("Felhasználónév")
                    ->searchable(),
                Tables\Columns\TextColumn::make("email")
                    ->label("E-mail cím")
                    ->searchable(),
                Tables\Columns\TextColumn::make("email_verified_at")
                    ->label("E-mail cím megerősítve")
                    ->placeholder("Nincs megerősítve")
                    ->dateTime()
                    ->since()
                    ->sortable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Létrehozva")
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("updated_at")
                    ->label("Frissítve")
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $query
                    ->whereSchoolId(auth()->user()->school_id)
                    ->whereRole(UserRole::SchoolManager);
            });
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
            "index" => Pages\ListUsers::route("/"),
        ];
    }
}
