<?php

namespace App\Filament\Organizer\Resources;

use App\Filament\Organizer\Resources\SchoolResource\Pages;
use App\Filament\Organizer\Resources\SchoolResource\RelationManagers;
use App\Models\School;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static ?string $navigationIcon = "heroicon-o-academic-cap";

    protected static ?string $label = "iskola";

    protected static ?string $pluralLabel = "iskolák";

    protected static ?string $navigationGroup = "Résztvevők";

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")
                    ->label("Név")
                    ->required()
                    ->maxLength(255),
                Forms\Components\Fieldset::make("Cím")
                    ->schema([
                        Forms\Components\TextInput::make("zip")
                            ->label("Irányítószám")
                            ->required()
                            ->mask("9999")
                            ->placeholder("0000")
                            ->maxLength(255)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $zip = $get("zip");
                                $station = \App\Models\Station::whereZip(
                                    $zip
                                )->first();
                                if ($station) {
                                    $set("city", $station->city);
                                    $set("state", $station->state);
                                }
                            })
                            ->datalist(function () {
                                return \App\Models\Station::all()
                                    ->pluck("zip")
                                    ->unique();
                            }),
                        Forms\Components\TextInput::make("city")
                            ->label("Város")
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $city = $get("city");
                                $station = \App\Models\Station::whereCity(
                                    $city
                                )->first();
                                if ($station) {
                                    $set("zip", $station->zip);
                                    $set("state", $station->state);
                                }
                            })
                            ->live()
                            ->datalist(function () {
                                return \App\Models\Station::all()
                                    ->pluck("city")
                                    ->unique();
                            })
                            ->maxLength(255),
                        Forms\Components\TextInput::make("state")
                            ->label("Vármegye")
                            ->required()
                            ->live()
                            ->maxLength(255),
                        Forms\Components\TextInput::make("street")
                            ->label("Utca, házszám")
                            ->required()
                            // datalist using state
                            ->datalist(function (Get $get) {
                                $zip = $get("zip");
                                return \App\Models\Street::whereZip($zip)
                                    ->whereNotIn("zip", [""])
                                    ->pluck("name")
                                    ->unique();
                            })
                            ->maxLength(255),
                    ])
                    ->columns(),
                Forms\Components\Fieldset::make("A kapcsolattartó adatai")
                    ->schema([
                        Forms\Components\TextInput::make("contact_name")
                            ->label("Kapcsolattartó neve")
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make("contact_email")
                            ->label("Kapcsolattartó e-mail címe")
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(),
            ])
            ->columns(1);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Grid::make(1)->schema([
                        Section::make([TextEntry::make("name")->label("Név")])
                            ->columns(1)
                            ->grow(),
                        Section::make("Cím")
                            ->schema([
                                TextEntry::make("zip")->label("Irányítószám"),
                                TextEntry::make("city")->label("Város"),
                                TextEntry::make("state")->label("Vármegye"),
                                TextEntry::make("street")->label(
                                    "Utca, házszám"
                                ),
                            ])
                            ->columns(),
                        Section::make("A kapcsolattartó adatai")
                            ->schema([
                                TextEntry::make("contact_name")->label(
                                    "Kapcsolattartó neve"
                                ),
                                TextEntry::make("contact_email")
                                    ->label("Kapcsolattartó e-mail címe")
                                    ->copyable(),
                            ])
                            ->columns()
                            ->headerActions([
                                Action::make("sendEmail")
                                    ->iconButton()
                                    ->icon("heroicon-m-envelope")
                                    ->outlined()
                                    ->url(function (School $record) {
                                        return "mailto:" .
                                            $record->contact_email;
                                    }),
                            ]),
                    ]),
                    Section::make([
                        TextEntry::make("created_at")
                            ->label("Létrehozva")
                            ->dateTime(),
                        TextEntry::make("updated_at")
                            ->label("Frissítve")
                            ->dateTime(),
                    ])->grow(false),
                ])->from("md"),
            ])
            ->columns(false);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->label("Név")
                    ->wrap()
                    ->lineClamp(2)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("address")
                    ->label("Cím")
                    ->wrap()
                    ->lineClamp(2)
                    ->state(function (School $record) {
                        return $record->zip .
                            " " .
                            $record->city .
                            " (" .
                            $record->state .
                            "), " .
                            $record->street;
                    }),
                Tables\Columns\TextColumn::make("contact_name")
                    ->label("Kapcsolattartó neve")
                    ->searchable(),
                Tables\Columns\TextColumn::make("contact_email")
                    ->label("Kapcsolattartó e-mail címe")
                    ->copyable()
                    ->searchable(),
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
        return [RelationManagers\TeamsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListSchools::route("/"),
            "create" => Pages\CreateSchool::route("/create"),
            "view" => Pages\ViewSchool::route("/{record}"),
            "edit" => Pages\EditSchool::route("/{record}/edit"),
        ];
    }
}
