<?php

namespace App\Filament\Organizer\Resources;

use App\Filament\Organizer\Resources\CategoryResource\RelationManagers\TeamsRelationManager;
use App\Filament\Organizer\Resources\ProgrammingLanguageResource\Pages;
use App\Filament\Organizer\Resources\ProgrammingLanguageResource\RelationManagers;
use App\Models\ProgrammingLanguage;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgrammingLanguageResource extends Resource
{
    protected static ?string $model = ProgrammingLanguage::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    public static function form(Form $form): Form
    {
        return $form->schema([TextInput::make("name")]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make()->disabled(
                    fn(ProgrammingLanguage $record) => $record
                        ->teams()
                        ->exists()
                ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [TeamsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListProgrammingLanguages::route("/"),
            "create" => Pages\CreateProgrammingLanguage::route("/create"),
            "edit" => Pages\EditProgrammingLanguage::route("/{record}/edit"),
        ];
    }
}
