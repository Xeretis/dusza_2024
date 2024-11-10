<?php

namespace App\Filament\SchoolManager\Pages;

use App\Enums\UserRole;
use App\Models\Station;
use App\Models\Street;
use App\Models\UserInvite;
use App\Notifications\UserInviteNotification;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Illuminate\Support\Facades\Auth;

class SchoolData extends Page implements HasForms
{
    use InteractsWithFormActions,
        CanUseDatabaseTransactions,
        InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.school-manager.pages.school-data';

    protected static ?string $navigationLabel = 'Iskola adatai';

    protected static ?string $pageTitle = 'Iskola adatai';

    public array $data = [];

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Mentés')
                ->submit('save'),
        ];
    }

    public function save()
    {
        $this->beginDatabaseTransaction();

        $this->validate();

        $data = $this->form->toArray();

        $school = Auth::user()->school;

        if (!$school) {
            abort(404);
        }

        $school->update([
            'name' => $data['name'],
            'zip' => $data['zip'],
            'city' => $data['city'],
            'state' => $data['state'],
            'street' => $data['street'],
            'contact_name' => $data['contact_name'],
            'contact_email' => $data['contact_email'],
        ]);

        if ($data['invite']) {
            $inv = UserInvite::create([
                'role' => UserRole::SchoolManager,
                'email' => $school->contact_email,
                'token' => Str::random(64),
                'school_id' => $school->id,
            ]);

            Notification::route('mail', $school->contact_email)->notify(
                new UserInviteNotification($inv->token)
            );
        }

        $this->commitDatabaseTransaction();

        $this->notify('Az iskola adatai sikeresen frissítve lettek.');

        $this->redirect(url()->current());
    }

    public function mount(): void
    {
        $this->fillForm();
        $this->form->columns(1);
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        $school = Auth::user()->school;

        if (!$school) {
            abort(404);
        }

        $this->data = [
            'name' => $school->name,
            'zip' => $school->zip,
            'city' => $school->city,
            'state' => $school->state,
            'street' => $school->street,
            'contact_name' => $school->contact_name,
            'contact_email' => $school->contact_email,
            'invite' => true,
        ];

        $this->form->fill($this->data);

        $this->callHook('afterFill');
    }

    public function form(Form $form): Form
    {
        return $form->schema($this->getFormSchema())->columns(1);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Név')
                ->required()
                ->maxLength(255),
            Forms\Components\Fieldset::make('Cím')
                ->schema([
                    Forms\Components\TextInput::make('zip')
                        ->label('Irányítószám')
                        ->required()
                        ->mask('9999')
                        ->placeholder('0000')
                        ->maxLength(255)
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $zip = $get('zip');
                            $station = Station::whereZip($zip)->first();
                            if ($station) {
                                $set('city', $station->city);
                                $set('state', $station->state);
                            }
                        }),
                    Forms\Components\TextInput::make('city')
                        ->label('Város')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $city = $get('city');
                            $station = Station::whereCity($city)->first();
                            if ($station) {
                                $set('zip', $station->zip);
                                $set('state', $station->state);
                            }
                        })
                        ->live()
                        ->datalist(function (Get $get) {
                            return Station::whereLike(
                                'city',
                                '%' . $get('city') . '%'
                            )
                                ->take(10)
                                ->pluck('city')
                                ->unique();
                        })
                        ->maxLength(255),
                    Forms\Components\TextInput::make('state')
                        ->label('Vármegye')
                        ->required()
                        ->live()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('street')
                        ->label('Utca, házszám')
                        ->required()
                        ->datalist(function (Get $get) {
                            $zip = $get('zip');
                            return Street::whereZip($zip)
                                ->whereNotIn('zip', [''])
                                ->whereLike('name', '%' . $get('street') . '%')
                                ->take(10)
                                ->pluck('name')
                                ->unique();
                        })
                        ->live()
                        ->maxLength(255),
                ])
                ->columns(),
            Forms\Components\Fieldset::make('A kapcsolattartó adatai')
                ->schema([
                    Forms\Components\TextInput::make('contact_name')
                        ->label('Kapcsolattartó neve')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('contact_email')
                        ->label('Kapcsolattartó e-mail címe')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Toggle::make('invite')
                        ->label('Felhasználó meghívása')
                        ->default(true)
                        ->required(),
                ])
                ->columns(),
        ];
    }
}
