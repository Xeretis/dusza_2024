<?php

namespace App\Filament\Organizer\Resources\TeamResource\Pages;

use App\Enums\TeamEventScope;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;
use App\Filament\Organizer\Resources\TeamResource;
use App\Models\Team;
use App\Models\TeamEvent;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewTeam extends ViewRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('requestAmendment')
                ->label('Módosítás kérvényezése')
                ->color('gray')
                ->form([
                    Textarea::make('message')->label('Üzenet')->required()
                ])
                ->action(function (array $data, Team $record) {
                    TeamEvent::create([
                        'message' => $data['message'],
                        'artifact_url' => '', //TODO: Do whatever you want with this
                        'team_id' => $record->id,
                        'scope' => TeamEventScope::Organizer,
                        'type' => TeamEventType::AmendRequest,
                        'status' => TeamEventStatus::Pending,
                    ]);

                    //TODO: Send out an email notification about this

                    Notification::make()->title('Kérvény elküldve')->success()->send();
                }),
            Actions\EditAction::make()
        ];
    }
}
