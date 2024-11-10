<?php

namespace App\Filament\Teacher\Pages;

use App\Enums\CompetitorProfileType;
use App\Models\CompetitorProfile;
use App\Models\School;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasMaxWidth;
use Filament\Pages\Concerns\HasTopbar;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class CreateCompetitorProfile extends Page
{
    use HasMaxWidth, HasTopbar, InteractsWithFormActions, CanUseDatabaseTransactions;

    protected static string $layout = 'filament-panels::components.layout.simple';
    protected static string $view = 'filament.teacher.pages.create-competitor-profile';
    protected static ?string $title = 'Add meg az adataid!';
    protected ?string $subheading = 'Ahhoz, hogy a diákok megtaláljanak, vagy hogy csapatot hozzhass létre, előbb meg kell adnod pár dolgot magadról.';
    public array $data;

    public static function canAccess(): bool
    {
        return CompetitorProfile::where('user_id', auth()->id())->doesntExist();
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function hasLogo(): bool
    {
        return true;
    }

    public function create()
    {
        $data = $this->form->getState();

        CompetitorProfile::create([
            'name' => $data['name'],
            'email' => auth()->user()->email,
            'type' => CompetitorProfileType::Teacher,
            'school_ids' => $data['school_ids'],
            'user_id' => auth()->id()
        ]);

        Notification::make()
            ->success()
            ->title('Profil sikeresen létrehozva!')
            ->send();

        $this->redirect(Filament::getPanel('teacher')->getUrl());
    }

    public function form(Form $form): Form
    {
        return $form->schema([
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
        ])->statePath('data');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    protected function getLayoutData(): array
    {
        return [
            'hasTopbar' => $this->hasTopbar(),
            'maxWidth' => $this->getMaxWidth(),
        ];
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
