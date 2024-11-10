<?php

namespace App\Filament\Teacher\Resources\TeamResource\RelationManagers;

use App\Enums\TeamEventResponseStatus;
use App\Enums\TeamEventScope;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;
use App\Enums\UserRole;
use App\Filament\Teacher\Resources\TeamResource\Pages\ViewTeam;
use App\Models\TeamEvent;
use App\Models\TeamEventResponse;
use App\Models\User;
use App\Notifications\AmendRequestUpdatedNotification;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification as FacadesNotification;

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
                    TextEntry::make('message')->label('Üzenet'),
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
                            TeamEventResponseStatus::Pending => 'Folyamatban',
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('response')
                    ->label('Válaszadás')
                    ->icon('heroicon-o-chat-bubble-oval-left')
                    ->color('secondary')
                    ->hidden(
                        fn(TeamEvent $teamEvent) => $teamEvent->type !==
                            TeamEventType::AmendRequest ||
                            ($teamEvent->status !== TeamEventStatus::Pending ||
                                $teamEvent->response !== null)
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
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('5s');
    }
}
