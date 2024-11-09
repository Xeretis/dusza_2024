<?php

namespace App\Filament\Organizer\Resources;

use App\Filament\Organizer\Resources\ProgrammingLanguageResource\Pages;
use App\Filament\Organizer\Resources\ProgrammingLanguageResource\RelationManagers;
use App\Models\ProgrammingLanguage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProgrammingLanguageResource extends Resource
{
    protected static ?string $model = ProgrammingLanguage::class;

    protected static ?string $navigationIcon = "heroicon-o-code-bracket";

    protected static ?string $label = 'programozási nyelv';

    protected static ?string $pluralLabel = 'programozási nyelvek';

    protected static ?string $navigationGroup = 'Verseny';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form->schema([TextInput::make("name")->label('Név')]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Split::make([
                Section::make([
                    TextEntry::make('name')
                        ->label('Név'),
                ])->columns()->grow(),
                Section::make([
                    TextEntry::make('created_at')
                        ->label('Létrehozva')
                        ->dateTime(),
                    TextEntry::make('updated_at')
                        ->label('Frissítve')
                        ->dateTime(),
                ])->grow(false),
            ])->from('md'),
        ])->columns(false);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->label('Név')
                    ->searchable()
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [RelationManagers\TeamsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListProgrammingLanguages::route("/"),
            "create" => Pages\CreateProgrammingLanguage::route("/create"),
            'view' => Pages\ViewProgrammingLanguage::route('/{record}'),
            "edit" => Pages\EditProgrammingLanguage::route("/{record}/edit"),
        ];
    }
}
