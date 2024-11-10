<?php

namespace App\Filament\Competitor\Pages;

use App\Enums\CompetitorProfileType;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\CompetitorProfile;
use App\Models\ProgrammingLanguage;
use App\Models\School;
use App\Models\Team;
use App\Models\User;
use App\Models\UserInvite;
use App\Notifications\UserInviteNotification;
use DragonCode\Support\Facades\Helpers\Str;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasMaxWidth;
use Filament\Pages\Concerns\HasTopbar;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Throwable;

class CreateTeam extends Page
{
    use HasMaxWidth, HasTopbar, InteractsWithFormActions, CanUseDatabaseTransactions;

    protected static string $layout = 'filament-panels::components.layout.simple';
    protected static string $view = 'filament.competitor.pages.create-team';
    protected static ?string $title = 'Hozz létre egy csapatot!';
    public array $data;

    public static function canAccess(): bool
    {
        return auth()->user()->teams()->count() == 0;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                ...self::teamDetailsSection(),
                Section::make('Résztvevők és egyéb adatok')
                    ->description('Ezeket az adatokat később is meg lehet adni, nem kötelező a cspata létrehozásakor.')
                    ->collapsible()
                    ->schema([
                        self::competitorSection('1. Csapattag - Saját adataid', 'competitor1', self: true),
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
                ->selectablePlaceholder(false),
        ];
    }

    private static function competitorSection(string $label, string $competitorKey, bool $isSubstitute = false, bool $self = false)
    {
        return Fieldset::make($label)->schema([
            Hidden::make("{$competitorKey}.id")->default(null),
            TextInput::make("{$competitorKey}.name")
                ->label('Név')
                ->required(!$isSubstitute)
                ->when($isSubstitute, function ($f) use ($competitorKey) {
                    return $f->requiredWith("{$competitorKey}.grade")
                        ->requiredWith("{$competitorKey}.email");
                }),
            TextInput::make("{$competitorKey}.grade")
                ->label('Évfolyam')
                ->numeric()
                ->minValue(0)
                ->required(!$isSubstitute)
                ->when($isSubstitute, function ($f) use ($competitorKey) {
                    return $f->requiredWith("{$competitorKey}.name")
                        ->requiredWith("{$competitorKey}.email");
                }),
            TextInput::make("{$competitorKey}.email")
                ->label('E-mail cím')
                ->hidden($self)
                ->hintIcon('heroicon-m-information-circle')
                ->hintIconTooltip('Felhasználó meghívásához szükséges megadni')
                ->email()
                ->unique('competitor_profiles', 'email', ignorable: function (Get $get) use ($competitorKey) {
                    if ($get("{$competitorKey}.id") != null) {
                        return CompetitorProfile::find($get("{$competitorKey}.id"));
                    }
                    return null;
                })
                ->live(onBlur: true),
            self::inviteToggle($competitorKey, $self),
        ]);
    }

    private static function inviteToggle(string $competitorKey, bool $self = false)
    {
        return Toggle::make("{$competitorKey}.invite")
            ->label('Felhasználó meghívása')
            ->hintIcon('heroicon-m-information-circle')
            ->hintIconTooltip(function (Get $get) use ($competitorKey) {
                $email = $get("{$competitorKey}.email");
                return $email && User::where('email', $email)->exists()
                    ? 'Már létezik felhasználó ezzel az e-mail címmel'
                    : 'E-mail küldése a megadott e-mail címre a regisztrációs linkkel. A meghívott felhasználó is tudni fogja kezelni a cspatot.';
            })
            ->inline(false)
            ->hidden($self)
            ->disabled(fn(Get $get) => !$get("{$competitorKey}.email") || User::where('email', $get("{$competitorKey}.email"))->exists());
    }

    private static function teachersSection()
    {
        $teachers = CompetitorProfile::where('type', CompetitorProfileType::Teacher)->pluck('name', 'id');

        return Fieldset::make('Felkészítő tanárok')
            ->schema([
                Repeater::make('teachers')
                    ->label('')
                    ->schema([
                        Select::make('id')
                            ->label('Név')
                            ->options($teachers->toArray())
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Név')
                                    ->required(),
                                TextInput::make('email')
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
                            ->createOptionUsing(function (array $data) {
                                return self::createTeacherProfile($data);
                            })
                            ->native(false)
                            ->distinct()
                            ->required()
                            ->selectablePlaceholder(false)
                            ->fixIndistinctState(),
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
            ->disabled(fn(Get $get) => !$get('email') || User::where('email', $get('email'))->exists());
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

    public function create()
    {
        $data = $this->form->getState();

        $model = Team::create([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'programming_language_id' => $data['programming_language_id'],
            'school_id' => $data['school_id'],
        ]);

        $this->createCompetitorProfile($data['competitor1'], $model, self: true);
        $this->createCompetitorProfile($data['competitor2'], $model);
        $this->createCompetitorProfile($data['competitor3'], $model);
        $this->createCompetitorProfile($data['substitute'], $model, true);

        foreach ($data['teachers'] as ['id' => $id]) {
            $model->competitorProfiles()->attach($id);
        }

        $this->redirect(Filament::getPanel('competitor')->getUrl());
    }

    private function createCompetitorProfile(array $competitorData, Team $teamModel, bool $isSubstitute = false, bool $self = false): void
    {
        if (!empty($competitorData['name'])) {

            $userId = $self ? null : User::where('email', $competitorData['email'])->first()?->id;

            try {
                DB::beginTransaction();
                $competitorProfile = CompetitorProfile::create(
                    collect($competitorData)
                        ->forget(['id', 'invite'])
                        ->merge([
                            'user_id' => $self ? auth()->id() : $userId,
                            'type' => $isSubstitute ? CompetitorProfileType::SubstituteStudent : CompetitorProfileType::Student,
                        ])
                        ->when($self, fn($c) => $c->merge(['email' => auth()->user()->email]))
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

                    Notification::route('mail', $competitorProfile['email'])->notify(new UserInviteNotification($inv->token));
                }

                DB::commit();
            } catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function mount()
    {
        $this->form->fill();
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
}
