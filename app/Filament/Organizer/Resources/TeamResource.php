<?php

namespace App\Filament\Organizer\Resources;

use App\Filament\Organizer\Resources\TeamResource\Pages;
use App\Filament\Organizer\Resources\TeamResource\RelationManagers;
use App\Livewire\TeamEventsActivitySection;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'csapat';

    protected static ?string $pluralLabel = 'Csapatok';

    protected static ?string $navigationGroup = 'Résztvevők';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->name('Név')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->label('Kategória')
                    ->relationship('category', 'name')
                    ->required()
                    ->native(false)
                    ->selectablePlaceholder(false),
                Forms\Components\Select::make('programming_language_id')
                    ->label('Programozási nyelv')
                    ->relationship('programmingLanguage', 'name')
                    ->required()
                    ->native(false)
                    ->selectablePlaceholder(false),
                Forms\Components\Select::make('school_id')
                    ->label('Iskola')
                    ->relationship('school', 'name')
                    ->required()
                    ->native(false)
                    ->selectablePlaceholder(false),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Grid::make(1)->schema([
                        Section::make([
                            TextEntry::make('name')
                                ->label('Név'),
                            TextEntry::make('category.name')
                                ->label('Kategória')
                                ->badge(),
                            TextEntry::make('programmingLanguage.name')
                                ->label('Programozási nyelv'),
                            TextEntry::make('school.name')
                                ->label('Iskola')
                        ])->columns()->grow(),
                    ]),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('school.name')
                    ->label('Iskola')
                    ->wrap()
                    ->lineClamp(2)
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EventsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'view' => Pages\ViewTeam::route('/{record}'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}