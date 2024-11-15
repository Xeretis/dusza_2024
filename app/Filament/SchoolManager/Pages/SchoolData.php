<?php

namespace App\Filament\SchoolManager\Pages;

use App\Enums\UserRole;
use App\Models\Station;
use App\Models\Street;
use App\Models\UserInvite;
use App\Notifications\UserInviteNotification;
use DragonCode\Support\Facades\Helpers\Str;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class SchoolData extends Page implements HasForms
{
    use InteractsWithFormActions, CanUseDatabaseTransactions, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-pencil';
    protected static string $view = 'filament.school-manager.pages.school-data';
    protected static ?string $navigationLabel = 'Iskola adatai';
    protected static ?string $title = 'Iskola adatai';
    protected static ?string $navigationGroup = 'Iskolai';

    public array $data = [];

    public function save()
    {
        $this->beginDatabaseTransaction();
        $this->validate();
        $data = $this->form->getState();
        $school = Auth::user()->school;

        if (!$school) {
            abort(404);
        }

        $school->update($this->getSchoolData($data));

        if ($data['invite']) {
            $this->sendInvite($school);
        }

        $this->commitDatabaseTransaction();

        \Filament\Notifications\Notification::make()
            ->success()
            ->title('Iskola adatai sikeresen frissítve!')
            ->send();
    }

    protected function getSchoolData(array $data): array
    {
        return [
            'name' => $data['name'],
            'zip' => $data['zip'],
            'city' => $data['city'],
            'state' => $data['state'],
            'street' => $data['street'],
            'contact_name' => $data['contact_name'],
            'contact_email' => $data['contact_email'],
        ];
    }

    protected function sendInvite($school)
    {
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

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $school = Auth::user()->school;

        if (!$school) {
            abort(404);
        }

        $this->data = $this->getSchoolDataArray($school);
        $this->form->fill($this->data);
    }

    protected function getSchoolDataArray($school): array
    {
        return [
            'name' => $school->name,
            'zip' => $school->zip,
            'city' => $school->city,
            'state' => $school->state,
            'street' => $school->street,
            'contact_name' => $school->contact_name,
            'contact_email' => $school->contact_email,
            'invite' => true,
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data')
            ->columns(1);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Név')
                ->required()
                ->maxLength(255),
            Forms\Components\Fieldset::make('Cím')
                ->schema($this->getAddressSchema())
                ->columns(),
            Forms\Components\Fieldset::make('A kapcsolattartó adatai')
                ->schema($this->getContactSchema())
                ->columns(),
        ];
    }

    protected function getAddressSchema(): array
    {
        return [
            Forms\Components\TextInput::make('zip')
                ->label('Irányítószám')
                ->required()
                ->mask('9999')
                ->placeholder('0000')
                ->maxLength(255)
                ->live()
                ->afterStateUpdated(fn(Get $get, Set $set) => $this->updateAddressState($get, $set, 'zip', 'city', 'state')),
            Forms\Components\TextInput::make('city')
                ->label('Város')
                ->required()
                ->live()
                ->afterStateUpdated(fn(Get $get, Set $set) => $this->updateAddressState($get, $set, 'city', 'zip', 'state'))
                ->datalist(fn(Get $get) => $this->getCityDatalist($get))
                ->maxLength(255),
            Forms\Components\TextInput::make('state')
                ->label('Vármegye')
                ->required()
                ->live()
                ->maxLength(255),
            Forms\Components\TextInput::make('street')
                ->label('Utca, házszám')
                ->required()
                ->datalist(fn(Get $get) => $this->getStreetDatalist($get))
                ->live()
                ->maxLength(255),
        ];
    }

    protected function updateAddressState(Get $get, Set $set, string $source, string $target1, string $target2): void
    {
        $value = $get($source);
        $station = Station::where($source, $value)->first();
        if ($station) {
            $set($target1, $station->$target1);
            $set($target2, $station->$target2);
        }
    }

    protected function getCityDatalist(Get $get)
    {
        return Station::whereLike('city', '%' . $get('city') . '%')
            ->take(10)
            ->pluck('city')
            ->unique();
    }

    protected function getStreetDatalist(Get $get)
    {
        $zip = $get('zip');
        return Street::whereZip($zip)
            ->whereNotIn('zip', [''])
            ->whereLike('name', '%' . $get('street') . '%')
            ->take(10)
            ->pluck('name')
            ->unique();
    }

    protected function getContactSchema(): array
    {
        return [
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
                ->label('Meghívó újraküldése')
                ->default(false)
                ->required(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Mentés')
                ->submit('save'),
        ];
    }
}
