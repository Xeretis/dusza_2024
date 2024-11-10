<?php

namespace App\Filament\Competitor\Pages;

use App\Enums\CompetitorProfileType;
use App\Enums\TeamStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\CompetitorProfile;
use App\Models\ProgrammingLanguage;
use App\Models\School;
use App\Models\Team;
use App\Models\User;
use App\Models\UserInvite;
use App\Notifications\TeamDataUpdatedNotification;
use App\Notifications\UserInviteNotification;
use DragonCode\Support\Facades\Helpers\Str;
use Filament\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasMaxWidth;
use Filament\Pages\Concerns\HasTopbar;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class EditTeam extends Page
{
    use HasMaxWidth;
    use HasTopbar;
    use InteractsWithFormActions;
    use CanUseDatabaseTransactions;

    protected static string $view = 'filament.competitor.pages.edit-team';
    protected static ?string $title = 'Csapat szerkesztése';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Jelentkezés';

    public array $data;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                ...self::teamDetailsSection(),
                Section::make('Résztvevők és egyéb adatok')
                    ->description(
                        'Ezeket az adatokat később is meg lehet adni, nem kötelező a cspata létrehozásakor.'
                    )
                    ->collapsible()
                    ->schema([
                        self::competitorSection(
                            '1. Csapattag - Saját adataid',
                            'competitor1',
                            self: true
                        ),
                        self::competitorSection('2. Csapattag', 'competitor2'),
                        self::competitorSection('3. Csapattag', 'competitor3'),
                        self::competitorSection('Póttag', 'substitute', true),
                        self::teachersSection(),
                    ]),
            ])
            ->statePath('data')
            ->columns();
    }

    private static function teamDetailsSection()
    {
        return [
            TextInput::make('name')
                ->label('Név')
                ->required()
                ->maxLength(255),
            Select::make('category_id')
                ->label('Kategória')
                ->options(Category::all()->pluck('name', 'id'))
                ->required()
                ->native(false)
                ->selectablePlaceholder(false),
            Select::make('programming_language_id')
                ->label('Programozási nyelv')
                ->options(ProgrammingLanguage::all()->pluck('name', 'id'))
                ->required()
                ->native(false)
                ->selectablePlaceholder(false),
            Select::make('school_id')
                ->label('Iskola')
                ->options(School::all()->pluck('name', 'id'))
                ->required()
                ->native(false)
                ->selectablePlaceholder(false)
                ->live(),
        ];
    }

    private static function competitorSection(
        string $label,
        string $competitorKey,
        bool   $isSubstitute = false,
        bool   $self = false
    )
    {
        return Fieldset::make($label)->schema([
            Hidden::make("{$competitorKey}.id")->default(null),
            TextInput::make("{$competitorKey}.name")
                ->label('Név')
                ->required(!$isSubstitute)
                ->when($isSubstitute, function ($f) use ($competitorKey) {
                    return $f
                        ->requiredWith("{$competitorKey}.grade")
                        ->requiredWith("{$competitorKey}.email");
                }),
            TextInput::make("{$competitorKey}.grade")
                ->label('Évfolyam')
                ->numeric()
                ->minValue(0)
                ->required(!$isSubstitute)
                ->when($isSubstitute, function ($f) use ($competitorKey) {
                    return $f
                        ->requiredWith("{$competitorKey}.name")
                        ->requiredWith("{$competitorKey}.email");
                }),
            TextInput::make("{$competitorKey}.email")
                ->label('E-mail cím')
                ->hidden($self)
                ->hintIcon('heroicon-m-information-circle')
                ->hintIconTooltip('Felhasználó meghívásához szükséges megadni')
                ->email()
                ->unique(
                    'competitor_profiles',
                    'email',
                    ignorable: function (Get $get) use ($competitorKey) {
                        if ($get("{$competitorKey}.id") != null) {
                            return CompetitorProfile::find(
                                $get("{$competitorKey}.id")
                            );
                        }

                        return null;
                    }
                )
                ->live(onBlur: true),
        ]);
    }

    private static function teachersSection()
    {
        $teachers = CompetitorProfile::where(
            'type',
            CompetitorProfileType::Teacher
        )->pluck('name', 'id');

        return Fieldset::make('Felkészítő tanárok')
            ->schema([
                Repeater::make('teachers')
                    ->label('')
                    ->schema([
                        Select::make('id')
                            ->label('Név')
                            ->options(function (Get $get) {
                                if ($get('school_only')) {
                                    return CompetitorProfile::where(
                                        'type',
                                        CompetitorProfileType::Teacher
                                    )
                                        ->whereJsonContains(
                                            'school_ids',
                                            intval($get('../../school_id'))
                                        )
                                        ->pluck('name', 'id');
                                }

                                return CompetitorProfile::where(
                                    'type',
                                    CompetitorProfileType::Teacher
                                )->pluck('name', 'id');
                            })
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Név')
                                    ->required(),
                                TextInput::make('email')
                                    ->label('E-mail cím')
                                    ->hintIcon('heroicon-m-information-circle')
                                    ->hintIconTooltip(
                                        'Felhasználó meghívásához szükséges megadni'
                                    )
                                    ->email()
                                    ->live(onBlur: true),
                                Select::make('school_ids')
                                    ->label('Iskolák')
                                    ->options(
                                        School::all()->pluck('name', 'id')
                                    )
                                    ->multiple()
                                    ->searchable()
                                    ->native(false)
                                    ->minItems(1)
                                    ->required()
                                    ->dehydrateStateUsing(
                                        fn($state) => collect($state)
                                            ->map(fn($e) => intval($e))
                                            ->toArray()
                                    ),
                                self::inviteToggleForTeacher(),
                            ])
                            ->createOptionUsing(function (array $data) {
                                try {
                                    $userId = User::where(
                                        'email',
                                        $data['email']
                                    )->first()?->id;

                                    DB::beginTransaction();

                                    $profileKey = CompetitorProfile::create([
                                        'name' => $data['name'],
                                        'email' => $data['email'],
                                        'school_ids' => $data['school_ids'],
                                        'type' =>
                                            CompetitorProfileType::Teacher,
                                        'user_id' => $userId,
                                    ])->getKey();

                                    if (
                                        isset($data['invite']) &&
                                        $data['invite']
                                    ) {
                                        $inv = UserInvite::create([
                                            'role' => UserRole::Teacher,
                                            'email' => $data['email'],
                                            'token' => Str::random(64),
                                            'competitor_profile_id' => $profileKey,
                                        ]);

                                        Notification::route(
                                            'mail',
                                            $data['email']
                                        )->notify(
                                            new UserInviteNotification(
                                                $inv->token
                                            )
                                        );
                                    }

                                    DB::commit();

                                    return $profileKey;
                                } catch (Throwable $e) {
                                    DB::rollBack();
                                    throw $e;
                                }
                            })
                            ->native(false)
                            ->distinct()
                            ->required()
                            ->selectablePlaceholder(false)
                            ->fixIndistinctState(),
                        Toggle::make('school_only')
                            ->label(
                                'Csak a megadott iskolai tanárainak megjelenítése'
                            )
                            ->dehydrated(false)
                            ->disabled(
                                fn(Get $get) => $get('../../school_id') == null
                            )
                            ->afterStateUpdated(
                                fn(Set $set) => $set('id', null)
                            )
                            ->live(),
                    ])
                    ->columns(1)
                    ->addActionLabel('Új tanár hozzáadása')
                    ->reorderable(false)
                    ->itemLabel('Új felkészítő tanár')
                    ->defaultItems(1)
                    ->minItems(1),
            ])
            ->columns(1);
    }

    private static function inviteToggleForTeacher()
    {
        return Toggle::make('invite')
            ->label('Felhasználó meghívása')
            ->hintIcon('heroicon-m-information-circle')
            ->hintIconTooltip(function (Get $get) {
                $email = $get('email');
                return $email && User::where('email', $email)->exists()
                    ? 'Már létezik felhasználó ezzel az e-mail címmel'
                    : 'E-mail küldése a megadott e-mail címre a regisztrációs linkkel';
            })
            ->inline(false)
            ->disabled(
                fn(Get $get) => !$get('email') ||
                    User::where('email', $get('email'))->exists()
            );
    }

    public function edit()
    {
        $data = $this->form->getState();

        $record = auth()
            ->user()
            ->teams()
            ->first();

        $record->update([
            ...collect($data)
                ->forget([
                    'competitor1',
                    'competitor2',
                    'competitor3',
                    'substitute',
                    'teachers',
                ])
                ->toArray(),
            'status' => TeamStatus::SchoolApproved,
        ]);

        if (isset($data['teachers'])) {
            $record->teachers()->detach();
            $record->teachers()->sync(
                collect($data['teachers'])
                    ->map(fn($t) => $t['id'])
                    ->toArray()
            );
        }

        $this->updateCompetitor($record, $data['competitor1'], self: true);
        $this->updateCompetitor($record, $data['competitor2']);
        $this->updateCompetitor($record, $data['competitor3']);
        $this->updateCompetitor($record, $data['substitute'], true);

        Notification::send(
            User::whereRole(UserRole::Organizer)->get(),
            new TeamDataUpdatedNotification($record)
        );

        \Filament\Notifications\Notification::make()
            ->success()
            ->title('Csapat sikeresen módosítva!')
            ->send();
    }

    protected function updateCompetitor(
        Model $record,
        array $competitorData,
        bool  $isSubstitute = false,
        bool  $self = false
    ): void
    {
        if ($competitorData['id'] == null && !empty($competitorData['name'])) {
            $userId = User::where('email', $competitorData['email'])->first()
                ?->id;

            $competitorProfile = CompetitorProfile::create(
                collect($competitorData)
                    ->forget(['id', 'invite'])
                    ->merge([
                        'user_id' => $userId,
                        'type' => $isSubstitute
                            ? CompetitorProfileType::SubstituteStudent
                            : CompetitorProfileType::Student,
                    ])
                    ->when(
                        $self,
                        fn($c) => $c->merge(['email' => auth()->user()->email])
                    )
                    ->toArray()
            );

            $competitorProfile->teams()->attach($record->id);
        } elseif (empty($competitorData['name'])) {
            CompetitorProfile::whereId($competitorData['id'])->delete();
        } else {
            $userId = $self
                ? null
                : User::where('email', $competitorData['email'])->first()?->id;

            $competitorProfile = CompetitorProfile::whereId(
                $competitorData['id']
            )->first();

            $competitorProfile->update(
                collect($competitorData)
                    ->forget(['id'])
                    ->merge([
                        'user_id' => $self ? auth()->id() : $userId,
                    ])
                    ->when(
                        $self,
                        fn($c) => $c->merge(['email' => auth()->user()->email])
                    )
                    ->toArray()
            );
            $competitorProfile->teams()->attach($record->id);
        }
    }

    public function mount()
    {
        if (self::canAccess()) {
            $this->form->fill(
                $this->mutateFormDataBeforeFill(
                    auth()
                        ->user()
                        ->teams()
                        ->first()
                        ->toArray()
                )
            );
        }
    }

    public static function canAccess(): bool
    {
        if (auth()->guest())
            return false;

        return auth()
                ->user()
                ->teams()
                ->count() == 1;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $members = CompetitorProfile::where(
            'type',
            CompetitorProfileType::Student
        )
            ->whereHas('teams', function ($query) use ($data) {
                $query->where('teams.id', $data['id']);
            })
            ->take(3)
            ->get();

        if ($members->count() > 0) {
            $data['competitor1'] = [
                'id' => $members[0]->id,
                'name' => $members[0]->name,
                'grade' => $members[0]->grade,
                'email' => $members[0]->email,
                'invite' => false,
            ];
        }

        if ($members->count() > 1) {
            $data['competitor2'] = [
                'id' => $members[1]->id,
                'name' => $members[1]->name,
                'grade' => $members[1]->grade,
                'email' => $members[1]->email,
                'invite' => false,
            ];
        }

        if ($members->count() > 2) {
            $data['competitor3'] = [
                'id' => $members[2]->id,
                'name' => $members[2]->name,
                'grade' => $members[2]->grade,
                'email' => $members[2]->email,
                'invite' => false,
            ];
        }

        $substitute = CompetitorProfile::where(
            'type',
            CompetitorProfileType::SubstituteStudent
        )
            ->whereHas('teams', function ($query) use ($data) {
                $query->where('teams.id', $data['id']);
            })
            ->first();

        if ($substitute != null) {
            $data['substitute'] = [
                'id' => $substitute->id,
                'name' => $substitute->name,
                'grade' => $substitute->grade,
                'email' => $substitute->email,
                'invite' => false,
            ];
        }

        $teachers = CompetitorProfile::where(
            'type',
            CompetitorProfileType::Teacher
        )
            ->whereHas('teams', function ($query) use ($data) {
                $query->where('teams.id', $data['id']);
            })
            ->get()
            ->map(function ($p) {
                return ['id' => $p->id];
            })
            ->values();

        $data['teachers'] = $teachers->toArray();

        return $data;
    }

    public function hasLogo(): bool
    {
        return true;
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    protected function getLayoutData(): array
    {
        return [
            'hasTopbar' => $this->hasTopbar(),
            'maxWidth' => $this->getMaxWidth(),
        ];
    }

    public function getMaxWidth(): MaxWidth|string|null
    {
        return MaxWidth::SevenExtraLarge;
    }

    protected function getFormActions(): array
    {
        return [$this->getCreateFormAction()];
    }

    public function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Mentés')
            ->submit('create');
    }

    private function createCompetitorProfile(
        array $competitorData,
        Team  $teamModel,
        bool  $isSubstitute = false,
        bool  $self = false
    ): void
    {
        $userId = $self
            ? null
            : User::where('email', $competitorData['email'])->first()?->id;

        try {
            DB::beginTransaction();

            $competitorProfile = CompetitorProfile::create(
                collect($competitorData)
                    ->forget(['id', 'invite'])
                    ->merge([
                        'user_id' => $self ? auth()->id() : $userId,
                        'type' => $isSubstitute
                            ? CompetitorProfileType::SubstituteStudent
                            : CompetitorProfileType::Student,
                    ])
                    ->when(
                        $self,
                        fn($c) => $c->merge(['email' => auth()->user()->email])
                    )
                    ->toArray()
            );

            $competitorProfile->teams()->attach($teamModel);

            if ($competitorData['invite'] ?? false) {
                $inv = UserInvite::create([
                    'role' => UserRole::Teacher,
                    'email' => $competitorProfile['email'],
                    'token' => Str::random(64),
                    'competitor_profile_id' => $competitorProfile->id,
                ]);

                Notification::route(
                    'mail',
                    $competitorProfile['email']
                )->notify(new UserInviteNotification($inv->token));
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
