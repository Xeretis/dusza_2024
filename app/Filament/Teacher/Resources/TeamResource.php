<?php

namespace App\Filament\Teacher\Resources;

use App\Enums\CompetitorProfileType;
use App\Enums\UserRole;
use App\Filament\Teacher\Resources\TeamResource\Pages;
use App\Filament\Teacher\Resources\TeamResource\RelationManagers;
use App\Models\CompetitorProfile;
use App\Models\School;
use App\Models\Team;
use App\Models\User;
use App\Models\UserInvite;
use App\Notifications\UserInviteNotification;
use DragonCode\Support\Facades\Helpers\Str;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\HtmlString;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $label = 'csapat';
    protected static ?string $pluralLabel = 'Csapatok';

    protected static ?string $navigationGroup = 'Jelentkezés';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ...self::teamDetailsSection(),
                Forms\Components\Section::make('Résztvevők és egyéb adatok')
                    ->description('Ezeket az adatokat később is meg lehet adni, nem kötelező a cspata létrehozásakor.')
                    ->collapsible()
                    ->schema([
                        self::competitorSection('1. Csapattag', 'competitor1'),
                        self::competitorSection('2. Csapattag', 'competitor2'),
                        self::competitorSection('3. Csapattag', 'competitor3'),
                        self::competitorSection('Póttag', 'substitute', true),
                        self::teachersSection(),
                    ]),
            ])
            ->columns();
    }

    private static function teamDetailsSection()
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Név')
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
                ->selectablePlaceholder(false)
                ->live(),
        ];
    }

    private static function competitorSection(string $label, string $competitorKey, bool $isSubstitute = false)
    {
        return Forms\Components\Fieldset::make($label)->schema([
            Forms\Components\Hidden::make("{$competitorKey}.id")->default(null),
            Forms\Components\TextInput::make("{$competitorKey}.name")
                ->label('Név')
                ->required(!$isSubstitute)
                ->when($isSubstitute, fn($f) => $f->requiredWith("{$competitorKey}.grade")->requiredWith("{$competitorKey}.email")),
            Forms\Components\TextInput::make("{$competitorKey}.grade")
                ->label('Évfolyam')
                ->numeric()
                ->minValue(0)
                ->required(!$isSubstitute)
                ->when($isSubstitute, fn($f) => $f->requiredWith("{$competitorKey}.name")->requiredWith("{$competitorKey}.email")),
            Forms\Components\TextInput::make("{$competitorKey}.email")
                ->label('E-mail cím')
                ->hintIcon('heroicon-m-information-circle')
                ->hintIconTooltip('Felhasználó meghívásához szükséges megadni')
                ->email()
                ->unique('competitor_profiles', 'email', ignorable: fn(Forms\Get $get) => $get("{$competitorKey}.id") ? CompetitorProfile::find($get("{$competitorKey}.id")) : null)
                ->live(onBlur: true),
            self::inviteToggle($competitorKey),
        ]);
    }

    private static function inviteToggle(string $competitorKey)
    {
        return Forms\Components\Toggle::make("{$competitorKey}.invite")
            ->label('Felhasználó meghívása')
            ->hintIcon('heroicon-m-information-circle')
            ->hintIconTooltip(fn(Forms\Get $get) => $get("{$competitorKey}.email") && User::where('email', $get("{$competitorKey}.email"))->exists() ? 'Már létezik felhasználó ezzel az e-mail címmel' : 'E-mail küldése a megadott e-mail címre a regisztrációs linkkel. A meghívott felhasználó is tudni fogja kezelni a cspatot.')
            ->inline(false)
            ->visible(fn($operation) => $operation == 'create')
            ->disabled(fn(Forms\Get $get) => !$get("{$competitorKey}.email") || User::where('email', $get("{$competitorKey}.email"))->exists());
    }

    private static function teachersSection()
    {
        return Forms\Components\Fieldset::make(fn($operation) => $operation == 'edit' ? 'Felkészítő tanárok' : 'További felkészítő tanárok')
            ->schema([
                Forms\Components\Repeater::make('teachers')
                    ->label('')
                    ->schema([
                        Forms\Components\Select::make('id')
                            ->label('Név')
                            ->options(fn(Forms\Get $get) => $get('school_only') ? CompetitorProfile::where('type', CompetitorProfileType::Teacher)->whereJsonContains('school_ids', $get('../../school_id'))->pluck('name', 'id') : CompetitorProfile::where('type', CompetitorProfileType::Teacher)->pluck('name', 'id'))
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('edit')
                                    ->label('Szerkesztés')
                                    ->icon('heroicon-c-pencil')
                                    ->color('gray')
                                    ->visible(fn($state) => $state == auth()->user()->competitorProfile->id)
                                    ->fillForm(fn() => [
                                        'name' => auth()->user()->competitorProfile->name,
                                        'school_ids' => auth()->user()->competitorProfile->school_ids
                                    ])
                                    ->form([
                                        TextInput::make('name')
                                            ->label('Név')
                                            ->hintIcon('heroicon-m-information-circle')
                                            ->hintIconTooltip('Kérjük, hogy a valódi neved add meg!')
                                            ->required(),
                                        Select::make('school_ids')
                                            ->label('Iskolák')
                                            ->options(School::all()->pluck('name', 'id'))
                                            ->multiple()
                                            ->searchable()
                                            ->native(false)
                                            ->minItems(1)
                                            ->required()
                                            ->dehydrateStateUsing(fn($state) => collect($state)->map(fn($e) => intval($e))->toArray())
                                    ])
                                    ->action(fn(array $data) => CompetitorProfile::find(auth()->user()->competitorProfile->id)->update($data))
                            )
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
                                Select::make('school_ids')
                                    ->label('Iskolák')
                                    ->options(School::all()->pluck('name', 'id'))
                                    ->multiple()
                                    ->searchable()
                                    ->native(false)
                                    ->minItems(1)
                                    ->required()
                                    ->dehydrateStateUsing(fn($state) => collect($state)->map(fn($e) => intval($e))->toArray()),
                                self::inviteToggleForTeacher(),
                            ])
                            ->createOptionUsing(fn(array $data) => self::createTeacherProfile($data))
                            ->native(false)
                            ->distinct()
                            ->required()
                            ->selectablePlaceholder(false)
                            ->fixIndistinctState(),
                        Forms\Components\Toggle::make('school_only')
                            ->label('Csak a megadott iskolai tanárainak megjelenítése')
                            ->dehydrated(false)
                            ->disabled(fn(Forms\Get $get) => $get('../../school_id') == null)
                            ->afterStateUpdated(fn(Forms\Set $set) => $set('id', null))
                            ->live()
                    ])
                    ->columns(1)
                    ->addActionLabel('Új tanár hozzáadása')
                    ->reorderable(false)
                    ->itemLabel('Új felkészítő tanár')
                    ->defaultItems(0)
                    ->minItems(fn($operation) => $operation == 'edit' ? 1 : 0),
            ])
            ->columns(1);
    }

    private static function inviteToggleForTeacher()
    {
        return Forms\Components\Toggle::make('invite')
            ->label('Felhasználó meghívása')
            ->hintIcon('heroicon-m-information-circle')
            ->hintIconTooltip(fn(Forms\Get $get) => $get('email') && User::where('email', $get('email'))->exists() ? 'Már létezik felhasználó ezzel az e-mail címmel' : 'E-mail küldése a megadott e-mail címre a regisztrációs linkkel')
            ->inline(false)
            ->disabled(fn(Forms\Get $get) => !$get('email') || User::where('email', $get('email'))->exists());
    }

    private static function createTeacherProfile(array $data)
    {
        try {
            $userId = User::where('email', $data['email'])->first()?->id;

            DB::beginTransaction();

            $profileKey = CompetitorProfile::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'school_ids' => $data['school_ids'],
                'type' => CompetitorProfileType::Teacher,
                'user_id' => $userId
            ])->getKey();

            if (isset($data['invite']) && $data['invite']) {
                $inv = UserInvite::create([
                    'role' => UserRole::Teacher,
                    'email' => $data['email'],
                    'token' => Str::random(64),
                    'competitor_profile_id' => $profileKey,
                ]);

                Notification::route('mail', $data['email'])->notify(new UserInviteNotification($inv->token));
            }

            DB::commit();

            return $profileKey;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Grid::make(1)->schema([
                        Section::make([
                            TextEntry::make('name')->label('Név'),
                            TextEntry::make('status')->label('Státusz')->badge(),
                            TextEntry::make('category.name')->label('Kategória')->badge(),
                            TextEntry::make('programmingLanguage.name')->label('Programozási nyelv'),
                            TextEntry::make('school.name')->label('Iskola'),
                        ])->columns(3)->grow(),
                    ]),
                    Section::make([
                        TextEntry::make('created_at')->label('Létrehozva')->dateTime(),
                        TextEntry::make('updated_at')->label('Frissítve')->dateTime(),
                    ])->grow(false),
                ])->from('md'),
            ])
            ->columns(false);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Státusz')->badge()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Kategória')->badge()->sortable(),
                Tables\Columns\TextColumn::make('programmingLanguage.name')->label('Programozási nyelv')->formatStateUsing(fn($state) => new HtmlString("<i>" . str($state)->sanitizeHtml() . "</i>"))->sortable(),
                Tables\Columns\TextColumn::make('school.name')->label('Iskola')->wrap()->lineClamp(2)->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Létrehozva')->dateTime()->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('Frissítve')->dateTime()->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->label('Kategória')->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('programmingLanguage')->label('Programozási nyelv')->relationship('programmingLanguage', 'name'),
                Tables\Filters\SelectFilter::make('school')->label('Iskola')->relationship('school', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('competitorProfiles', fn($builder) => $builder->where('user_id', auth()->id())));
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CompetitorProfilesRelationManager::class,
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
