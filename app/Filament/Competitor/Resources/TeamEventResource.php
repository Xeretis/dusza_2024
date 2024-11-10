<?php

namespace App\Filament\Competitor\Resources;

use App\Enums\TeamEventScope;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;
use App\Filament\Competitor\Resources\TeamEventResource\Pages;
use App\Filament\Competitor\Resources\TeamEventResource\RelationManagers;
use App\Models\TeamEvent;
use DragonCode\Contracts\Cashier\Auth\Auth;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class TeamEventResource extends Resource
{
    protected static ?string $model = TeamEvent::class;

    protected static ?string $pluralLabel = 'események';

    protected static ?string $label = 'esemény';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            //
        ]);
    }

    public static function table(Table $table): Table
    {
        $team_id = FacadesAuth::user()->competitorProfile->teams->first()->id;

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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Létrehozva')
                    ->date()
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(
                fn(Builder $query) => $query->where('team_id', $team_id)
            )
            ->actions([Tables\Actions\ViewAction::make()])
            ->poll('5s');
    }

    public static function infolist(Infolist $infolist): Infolist
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
                Grid::make(1)->schema([
                    TextEntry::make('response.message')
                        ->html(true)
                        ->formatStateUsing(function ($state) {
                            return str($state)
                                ->markdown()
                                ->sanitizeHtml()
                                ->toHtmlString();
                        })
                        ->label('Válasz'),
                ]),
            ])
            ->columns();
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeamEvents::route('/'),
            'view' => Pages\ViewTeamEvent::route('/{record}'),
        ];
    }
}
