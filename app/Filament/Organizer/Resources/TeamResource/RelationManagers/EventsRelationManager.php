<?php

namespace App\Filament\Organizer\Resources\TeamResource\RelationManagers;

use App\Enums\TeamEventResponseStatus;
use App\Enums\TeamEventScope;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;
use App\Enums\TeamStatus;
use App\Filament\Organizer\Resources\TeamResource\Pages\ViewTeam;
use App\Models\TeamEvent;
use Filament\Forms\Components\Livewire;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $title = 'Események';

    public static function canViewForRecord(
        Model $ownerRecord,
        string $pageClass
    ): bool {
        return $pageClass == ViewTeam::class;
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('type')
                    ->label('Típus')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            TeamEventType::AmendRequest => 'Módosítási kérvény',
                            TeamEventType::Approval => 'Elfogadás',
                        };
                    }),
                TextEntry::make('scope')
                    ->label('Kezdeményező')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            TeamEventScope::School => 'Iskola menedzser',
                            TeamEventScope::Organizer => 'Szervező',
                        };
                    }),
                TextEntry::make('status')
                    ->label('Állapot')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            TeamEventStatus::Pending => 'Folyamatban',
                            TeamEventStatus::Approved => 'Elfogadva',
                            TeamEventStatus::Rejected => 'Elutasítva',
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            TeamEventStatus::Pending => 'warning',
                            TeamEventStatus::Approved => 'success',
                            TeamEventStatus::Rejected => 'danger',
                        };
                    })
                    ->badge(),
                TextEntry::make('created_at')
                    ->label('Létrehozva')
                    ->dateTime(),
                Grid::make(1)->schema([
                    TextEntry::make('message')
                        ->html(true)
                        ->formatStateUsing(function ($state) {
                            return str($state)
                                ->markdown()
                                ->sanitizeHtml()
                                ->toHtmlString();
                        })
                        ->label('Üzenet'),
                ]),
                Grid::make(1)
                    ->schema([
                        TextEntry::make('response.message')->label('Válasz'),
                    ])
                    ->hidden(fn(TeamEvent $record) => !$record->response),
            ])
            ->columns();
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->recordTitle(function (TeamEvent $record) {
                return match ($record->type) {
                    TeamEventType::AmendRequest => 'Módosítási kérvény',
                    TeamEventType::Approval => 'Elfogadás',
                };
            })
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Típus')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            TeamEventType::AmendRequest => 'Módosítási kérvény',
                            TeamEventType::Approval => 'Elfogadás',
                        };
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('scope')
                    ->label('Kezdeményező')
                    ->weight(FontWeight::Bold)
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            TeamEventScope::School => 'Iskola menedzser',
                            TeamEventScope::Organizer => 'Szervező',
                        };
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Állapot')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            TeamEventStatus::Pending => 'Folyamatban',
                            TeamEventStatus::Approved => 'Elfogadva',
                            TeamEventStatus::Rejected => 'Elutasítva',
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            TeamEventStatus::Pending => 'warning',
                            TeamEventStatus::Approved => 'success',
                            TeamEventStatus::Rejected => 'danger',
                        };
                    })
                    ->badge(),
                Tables\Columns\TextColumn::make('response.status')
                    ->label('Válasz állapota')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            TeamEventResponseStatus::Pending
                                => 'Válasz elérhető',
                            TeamEventResponseStatus::Approved => 'Elfogadva',
                            TeamEventResponseStatus::Rejected => 'Elutasítva',
                            'invalid' => 'Nem értelmezhető',
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            TeamEventResponseStatus::Pending => 'warning',
                            TeamEventResponseStatus::Approved => 'success',
                            TeamEventResponseStatus::Rejected => 'danger',
                            'invalid' => 'primary',
                        };
                    })
                    ->default('invalid')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Létrehozva')
                    ->date()
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()->modalFooterActions(
                    fn(TeamEvent $event) => [
                        Tables\Actions\Action::make('approve')
                            ->label('Elfogadás')
                            ->disabled(
                                $event->status !== TeamEventStatus::Pending ||
                                    !$event->response
                            )
                            ->modal('send-response')
                            ->requiresConfirmation()
                            ->action(function (TeamEvent $event) {
                                $event->status = TeamEventStatus::Approved;
                                $event->save();
                                $event->response->status =
                                    TeamEventResponseStatus::Approved;
                                $event->response->save();

                                $event->team->status =
                                    TeamStatus::OrganizerApproved;
                                $event->team->save();

                                // Send notification

                                Notification::make()
                                    ->title('A változtatás elfogadva.')
                                    ->send($event->team->organizer);
                            })
                            ->color('danger'),
                        Tables\Actions\Action::make('decline')
                            ->label('Visszautasítás')
                            ->requiresConfirmation()
                            ->disabled(
                                $event->status !== TeamEventStatus::Pending ||
                                    !$event->response
                            )
                            ->action(function (TeamEvent $event) {
                                $event->status = TeamEventStatus::Rejected;
                                $event->save();
                                $event->response->status =
                                    TeamEventResponseStatus::Rejected;
                                $event->response->save();

                                // Send notification

                                Notification::make()
                                    ->title('A változtatás elutasítva.')
                                    ->send($event->team->organizer);
                            })
                            ->modal('send-response'),
                    ]
                ),
            ])
            ->poll('5s');
    }
}
