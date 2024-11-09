<?php

namespace App\Filament\Organizer\Resources\TeamResource\RelationManagers;

use App\Enums\TeamEventScope;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;
use App\Filament\Organizer\Resources\TeamResource\Pages\ViewTeam;
use App\Models\TeamEvent;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'teamEvents';

    protected static ?string $title = 'Események';


    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass == ViewTeam::class;
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('type')
                ->label('Típus')
                ->formatStateUsing(function ($state) {
                    return match ($state) {
                        TeamEventType::AmendRequest => 'Módosítási kérvény',
                        TeamEventType::Approval => 'Elfogadás'
                    };
                }),
            TextEntry::make('scope')
                ->label('Kezdeményező')
                ->formatStateUsing(function ($state) {
                    return match ($state) {
                        TeamEventScope::School => 'Iskola menedzser',
                        TeamEventScope::Organizer => 'Szervező'
                    };
                }),
            TextEntry::make('status')
                ->label('Állapot')
                ->formatStateUsing(function ($state) {
                    return match ($state) {
                        TeamEventStatus::Pending => 'Folyamatban',
                        TeamEventStatus::Completed => 'Befejezve',
                        TeamEventStatus::Approved => 'Elfogadva',
                        TeamEventStatus::Rejected => 'Elutasítva',
                    };
                })
                ->color(function ($state) {
                    return match ($state) {
                        TeamEventStatus::Pending => 'warning',
                        TeamEventStatus::Completed => 'primary',
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
                    ->label('Üzenet')
            ])
        ])->columns();
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->recordTitle(function (TeamEvent $record) {
                return match ($record->type) {
                    TeamEventType::AmendRequest => 'Módosítási kérvény',
                    TeamEventType::Approval => 'Elfogadás'
                };
            })
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Típus')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            TeamEventType::AmendRequest => 'Módosítási kérvény',
                            TeamEventType::Approval => 'Elfogadás'
                        };
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('scope')
                    ->label('Kezdeményező')
                    ->weight(FontWeight::Bold)
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            TeamEventScope::School => 'Iskola menedzser',
                            TeamEventScope::Organizer => 'Szervező'
                        };
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Állapot')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            TeamEventStatus::Pending => 'Folyamatban',
                            TeamEventStatus::Completed => 'Befejezve',
                            TeamEventStatus::Approved => 'Elfogadva',
                            TeamEventStatus::Rejected => 'Elutasítva',
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            TeamEventStatus::Pending => 'warning',
                            TeamEventStatus::Completed => 'primary',
                            TeamEventStatus::Approved => 'success',
                            TeamEventStatus::Rejected => 'danger',
                        };
                    })
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Létrehozva')
                    ->date()
                    ->since()
                    ->sortable()
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
