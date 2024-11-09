<?php

namespace App\Filament\Organizer\Resources;

use App\Enums\CompetitorProfileType;
use App\Filament\Organizer\Resources\TeamResource\Pages;
use App\Filament\Organizer\Resources\TeamResource\RelationManagers;
use App\Livewire\TeamEventsActivitySection;
use App\Models\CompetitorProfile;
use App\Models\Team;
use App\Models\User;
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
                Forms\Components\Section::make('Résztvevők és egyéb adatok')
                    ->description('Ezeket az adatokat később is meg lehet adni, nem kötelező a cspata létrehozásakor.')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Fieldset::make('1. Csapattag')->schema([
                            Forms\Components\TextInput::make('competitor1.name')
                                ->label('Név')
                                ->requiredWith('competitor1.grade')
                                ->requiredWith('competitor1.email'),
                            Forms\Components\TextInput::make('competitor1.grade')
                                ->label('Évfolyam')
                                ->requiredWith('competitor1.name')
                                ->requiredWith('competitor1.email'),
                            Forms\Components\TextInput::make('competitor1.email')
                                ->label('E-mail cím')
                                ->hintIcon('heroicon-m-information-circle')
                                ->hintIconTooltip('Felhasználó meghívásához szükséges megadni')
                                ->email()
                                ->live(onBlur: true),
                            Forms\Components\Toggle::make('competitor1.invite')
                                ->label('Felhasználó meghívása')
                                ->hintIcon('heroicon-m-information-circle')
                                ->hintIconTooltip(function (Forms\Get $get) {
                                    if ($get('competitor1.email') != null && User::where('email', $get('competitor1.email'))->exists()) {
                                        return 'Már létezik felhasználó ezzel az e-mail címmel';
                                    }

                                    return 'E-mail küldése a megadott e-mail címre a regisztrációs linkkel';
                                })
                                ->inline(false)
                                ->disabled(fn(Forms\Get $get) => $get('competitor1.email') == null || User::where('email', $get('competitor1.email'))->exists())
                        ]),
                        Forms\Components\Fieldset::make('2. Csapattag')->schema([
                            Forms\Components\TextInput::make('competitor2.name')
                                ->label('Név')
                                ->requiredWith('competitor2.grade')
                                ->requiredWith('competitor2.email'),
                            Forms\Components\TextInput::make('competitor2.grade')
                                ->label('Évfolyam')
                                ->requiredWith('competitor2.name')
                                ->requiredWith('competitor2.email'),
                            Forms\Components\TextInput::make('competitor2.email')
                                ->label('E-mail cím')
                                ->hintIcon('heroicon-m-information-circle')
                                ->hintIconTooltip('Felhasználó meghívásához szükséges megadni')
                                ->email()
                                ->live(onBlur: true),
                            Forms\Components\Toggle::make('competitor2.invite')
                                ->label('Felhasználó meghívása')
                                ->hintIcon('heroicon-m-information-circle')
                                ->hintIconTooltip(function (Forms\Get $get) {
                                    if ($get('competitor2.email') != null && User::where('email', $get('competitor2.email'))->exists()) {
                                        return 'Már létezik felhasználó ezzel az e-mail címmel';
                                    }

                                    return 'E-mail küldése a megadott e-mail címre a regisztrációs linkkel';
                                })
                                ->inline(false)
                                ->disabled(fn(Forms\Get $get) => $get('competitor2.email') == null || User::where('email', $get('competitor2.email'))->exists())
                        ]),
                        Forms\Components\Fieldset::make('3. Csapattag')->schema([
                            Forms\Components\TextInput::make('competitor3.name')
                                ->label('Név')
                                ->requiredWith('competitor3.grade')
                                ->requiredWith('competitor3.email'),
                            Forms\Components\TextInput::make('competitor3.grade')
                                ->label('Évfolyam')
                                ->requiredWith('competitor3.name')
                                ->requiredWith('competitor3.email'),
                            Forms\Components\TextInput::make('competitor3.email')
                                ->label('E-mail cím')
                                ->hintIcon('heroicon-m-information-circle')
                                ->hintIconTooltip('Felhasználó meghívásához szükséges megadni')
                                ->email()
                                ->live(onBlur: true),
                            Forms\Components\Toggle::make('competitor3.invite')
                                ->label('Felhasználó meghívása')
                                ->hintIcon('heroicon-m-information-circle')
                                ->hintIconTooltip(function (Forms\Get $get) {
                                    if ($get('competitor3.email') != null && User::where('email', $get('competitor3.email'))->exists()) {
                                        return 'Már létezik felhasználó ezzel az e-mail címmel';
                                    }

                                    return 'E-mail küldése a megadott e-mail címre a regisztrációs linkkel';
                                })
                                ->inline(false)
                                ->disabled(fn(Forms\Get $get) => $get('competitor3.email') == null || User::where('email', $get('competitor3.email'))->exists())
                        ]),
                        Forms\Components\Fieldset::make('Felkészítő tanárok')->schema([
                            Forms\Components\Repeater::make('teachers')
                                ->label('')
                                ->schema([
                                    Forms\Components\Select::make('id')
                                        ->label('Név')
                                        ->options(CompetitorProfile::where('type', CompetitorProfileType::Teacher)->pluck('name', 'id'))
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('name')
                                                ->label('Név')
                                                ->required(),
                                            Forms\Components\TextInput::make('email')
                                                ->label('E-mail cím')
                                                ->hintIcon('heroicon-m-information-circle')
                                                ->hintIconTooltip('Felhasználó meghívásához szükséges megadni')
                                                ->email()
                                                ->live(onBlur: true),
                                            Forms\Components\Toggle::make('invite')
                                                ->label('Felhasználó meghívása')
                                                ->hintIcon('heroicon-m-information-circle')
                                                ->hintIconTooltip(function (Forms\Get $get) {
                                                    if ($get('email') != null && User::where('email', $get('email'))->exists()) {
                                                        return 'Már létezik felhasználó ezzel az e-mail címmel';
                                                    }

                                                    return 'E-mail küldése a megadott e-mail címre a regisztrációs linkkel';
                                                })
                                                ->inline(false)
                                                ->disabled(fn(Forms\Get $get) => $get('email') == null || User::where('email', $get('email'))->exists())
                                        ])
                                        ->createOptionUsing(function (array $data): int {
                                            //TODO: Send invite out
                                            return CompetitorProfile::create([
                                                'name' => $data['name'],
                                                'email' => $data['email'],
                                                'type' => CompetitorProfileType::Teacher,
                                            ])->getKey();
                                        })
                                        ->distinct()
                                        ->fixIndistinctState(),
                                ])->columns(1)->addActionLabel('Új tanár hozzáadása'),
                        ])->columns(1)
                    ])
                    ->columns(2)
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
