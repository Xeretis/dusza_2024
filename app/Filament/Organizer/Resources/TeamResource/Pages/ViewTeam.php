<?php

namespace App\Filament\Organizer\Resources\TeamResource\Pages;

use App\Enums\TeamEventScope;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;
use App\Enums\TeamStatus;
use App\Filament\Organizer\Resources\TeamResource;
use App\Models\Team;
use App\Models\TeamEvent;
use App\Notifications\AmendRequestNotification;
use Filament\Actions;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class ViewTeam extends ViewRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('forceActive')
                ->label('Elfogadás kényszerítése')
                ->tooltip(
                    'A csapat elfogadásának kényszerítése. Minden korábbi kérvényt figyelmen kívül hagy.'
                )
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (array $data, Team $record, $livewire) {
                    $record->status = TeamStatus::OrganizerApproved;
                    $record->save();

                    TeamEvent::create([
                        'message' => 'A csapat elfogadása kényszerítve lett.',
                        'team_id' => $record->id,
                        'scope' => TeamEventScope::Organizer,
                        'type' => TeamEventType::Approval,
                        'status' => TeamEventStatus::Approved,
                        'user_id' => auth()->user()->id,
                        'closed_at' => now(),
                    ]);

                    TeamEvent::whereTeamId($record->id)->update([
                        'status' => TeamEventStatus::Approved,
                        'closed_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Csapat aktiválva')
                        ->success()
                        ->send();
                })
                ->disabled(
                    fn(Team $record) => $record->status ===
                        TeamStatus::OrganizerApproved
                ),
            Actions\Action::make('requestAmendment')
                ->label('Módosítás kérvényezése')
                ->color('gray')
                ->form([
                    MarkdownEditor::make('message')
                        ->label('Üzenet')
                        ->required(),
                ])
                ->disabled(
                    fn(Team $record) => $record->status ===
                        TeamStatus::Inactive ||
                        $record
                            ->events()
                            ->where('type', TeamEventType::AmendRequest)
                            ->where('status', TeamEventStatus::Pending)
                            ->exists()
                )
                ->action(function (array $data, Team $record) {
                    $event = TeamEvent::create([
                        'message' => $data['message'],
                        'team_id' => $record->id,
                        'scope' => TeamEventScope::Organizer,
                        'type' => TeamEventType::AmendRequest,
                        'status' => TeamEventStatus::Pending,
                        'user_id' => auth()->id(),
                    ]);

                    $record->status = TeamStatus::SchoolApproved;
                    $record->save();

                    $event = new AmendRequestNotification($event);

                    FacadesNotification::send(
                        $record->competitorProfiles,
                        $event
                    );

                    FacadesNotification::send(
                        $record->competitorProfiles
                            ->map(fn($profile) => $profile->user)
                            ->unique()
                            ->filter(),
                        $event
                    );

                    //TODO: Send out an email notification about this

                    Notification::make()
                        ->title('Kérvény elküldve')
                        ->success()
                        ->send();
                }),
            Actions\EditAction::make(),
        ];
    }
}
