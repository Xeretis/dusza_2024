<?php

namespace App\Filament\Competitor\Resources\TeamEventResource\Pages;

use App\Enums\TeamEventResponseStatus;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;
use App\Enums\UserRole;
use App\Filament\Competitor\Resources\TeamEventResource;
use App\Models\TeamEvent;
use App\Models\TeamEventResponse;
use App\Models\User;
use App\Notifications\AmendRequestUpdatedNotification;
use App\Notifications\TeamDataUpdatedNotification;
use Filament\Actions;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class ViewTeamEvent extends ViewRecord
{
    protected static string $resource = TeamEventResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()
            ->user()
            ->teams()
            ->first()
            ->events()
            ->where('id', $parameters['record']->id)
            ->exists();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reply')
                ->label('Válaszadás')
                ->hidden(
                    fn(TeamEvent $teamEvent) => $teamEvent->type !==
                        TeamEventType::AmendRequest
                )
                ->disabled(
                    fn(TeamEvent $teamEvent) => $teamEvent->status !==
                        TeamEventStatus::Pending ||
                        $teamEvent->response !== null
                )
                ->form([
                    MarkdownEditor::make('message')
                        ->label('Üzenet')
                        ->required(),
                ])
                ->action(function (array $data, TeamEvent $record) {
                    TeamEventResponse::create([
                        'team_event_id' => $record->id,
                        'message' => $data['message'],
                        'status' => TeamEventResponseStatus::Pending,
                        'changes' => [],
                    ]);

                    FacadesNotification::send(
                        User::whereRole(UserRole::Organizer)->get(),
                        new AmendRequestUpdatedNotification($record->team)
                    );

                    Notification::make()
                        ->title('Válasz sikeresen elküldve')
                        ->success()
                        ->send();
                }),
        ];
    }
}
