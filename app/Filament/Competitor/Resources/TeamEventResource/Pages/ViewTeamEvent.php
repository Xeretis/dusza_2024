<?php

namespace App\Filament\Competitor\Resources\TeamEventResource\Pages;

use App\Enums\TeamEventResponseStatus;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;
use App\Filament\Competitor\Resources\TeamEventResource;
use App\Models\TeamEvent;
use App\Models\TeamEventResponse;
use Filament\Actions;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewTeamEvent extends ViewRecord
{
    protected static string $resource = TeamEventResource::class;

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
                    // TODO: NOTIFICATION

                    $model = TeamEventResponse::create([
                        'team_event_id' => $record->id,
                        'message' => $data['message'],
                        'status' => TeamEventResponseStatus::Pending,
                        'changes' => [],
                    ]);

                    // $model->notify(new TeamEventResponseNotification($model));

                    Notification::make()
                        ->title('Válasz sikeresen elküldve')
                        ->success()
                        ->send();
                }),
        ];
    }
}
