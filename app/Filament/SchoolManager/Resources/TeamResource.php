<?php

namespace App\Filament\SchoolManager\Resources;

use App\Filament\SchoolManager\Resources\TeamResource\Pages;
use App\Filament\SchoolManager\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = "heroicon-o-user-group";

    protected static ?string $label = "csapat";

    protected static ?string $pluralLabel = "Csapatok";

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
                Tables\Columns\TextColumn::make("name")->searchable(),
                Tables\Columns\TextColumn::make("category.name")
                    ->label("Kategória")
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make("programmingLanguage.name")
                    ->label("Programozási nyelv")
                    ->formatStateUsing(function ($state) {
                        $sanitized = str($state)->sanitizeHtml();
                        return new HtmlString("<i>{$sanitized}</i>");
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make("status")
                    ->label("Státusz")
                    ->badge(),
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
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereSchoolId(auth()->user()->school_id);
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
            "index" => Pages\ListTeams::route("/"),
            "view" => Pages\ViewTeam::route("/{record}"),
        ];
    }
}
