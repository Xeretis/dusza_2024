<?php

namespace App\Filament\Organizer\Pages;

use App\Settings\CompetitionSettings;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageCompetition extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = CompetitionSettings::class;

    protected static ?string $navigationLabel = 'Verseny Kezelése';

    protected static ?string $title = 'Verseny kezelése';

    protected static ?string $navigationGroup = 'Verseny';

    protected static ?int $navigationSort = 3;

    public function getFormActions(): array
    {
        return [
            Action::make('endRegistration')
                ->label('Jelentkezés lezárása')
                ->icon('heroicon-m-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (CompetitionSettings $settings) {
                    $settings->registration_cancelled_at = now();
                    $settings->save();
                })->visible(fn(CompetitionSettings $settings) => $settings->registration_cancelled_at == null),
            Action::make('resumeRegistration')
                ->label('Jelentkezés megnyitása')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (CompetitionSettings $settings) {
                    $settings->registration_cancelled_at = null;
                    $settings->save();
                })->visible(fn(CompetitionSettings $settings) => $settings->registration_cancelled_at != null),
            $this->getSaveFormAction()
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Verseny neve'),
                Forms\Components\RichEditor::make('description')
                    ->label('Verseny leírása')
                    ->fileAttachmentsDisk('public')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h1',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ]),
                Forms\Components\DateTimePicker::make('registration_deadline')
                    ->label('A jelentkezés határideje')
                    ->native(false),
                Forms\Components\Placeholder::make('registrations_open')
                    ->label('A jelentkezés állapota')
                    ->content(fn(CompetitionSettings $settings) => Carbon::parse($settings->registration_deadline)->isAfter(now()) && $settings->registration_cancelled_at == null ? 'Engedélyezett' : 'Tiltott')
            ])->columns(1);
    }
}
