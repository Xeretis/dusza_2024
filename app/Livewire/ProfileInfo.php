<?php

namespace App\Livewire;

use App\Enums\CompetitorProfileType;
use App\Models\CompetitorProfile;
use App\Models\School;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class ProfileInfo extends MyProfileComponent
{
    public static $sort = 11;
    public array $data;
    public array $only = ['name', 'grade', 'school_ids'];
    public ?CompetitorProfile $competitorProfile;
    protected string $view = 'livewire.profile-info';

    public function mount()
    {
        $this->competitorProfile = Filament::getCurrentPanel()->auth()->user()->competitorProfile;

        if ($this->competitorProfile != null) {
            $this->form->fill($this->competitorProfile->only($this->only));
        }
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Név')
                    ->hintIcon('heroicon-m-information-circle')
                    ->hintIconTooltip('Kérjük, hogy a valódi neved add meg!')
                    ->required(),
                TextInput::make('grade')
                    ->label('Évfolyam')
                    ->visible($this->competitorProfile->type == CompetitorProfileType::Student)
                    ->numeric()
                    ->required(),
                Select::make('school_ids')
                    ->label('Iskolák')
                    ->options(School::all()->pluck('name', 'id'))
                    ->multiple()
                    ->searchable()
                    ->native(false)
                    ->minItems(1)
                    ->required()
                    ->visible($this->competitorProfile->type == CompetitorProfileType::Teacher)
                    ->dehydrateStateUsing(fn($state) => collect($state)->map(fn($e) => intval($e))->toArray())
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->competitorProfile->update($data);
        Notification::make()
            ->success()
            ->title(__('Custom component updated successfully'))
            ->send();
    }
}
