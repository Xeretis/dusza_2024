<?php

namespace App\Filament\Teacher\Resources\TeamResource\RelationManagers;

use App\Enums\TeamEventResponseStatus;
use App\Enums\TeamEventScope;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;
use App\Filament\Teacher\Resources\TeamResource\Pages\ViewTeam;
use App\Models\TeamEvent;
use App\Models\TeamEventResponse;
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

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';
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
        return $infolist->schema($this->getInfolistSchema())->columns();
    }

    protected function getInfolistSchema(): array
    {
        return [
            TextEntry::make('type')
                ->label('Típus')
                ->formatStateUsing(fn($state) => $this->formatType($state)),
            TextEntry::make('scope')
                ->label('Kezdeményező')
                ->formatStateUsing(fn($state) => $this->formatScope($state)),
            TextEntry::make('status')
                ->label('Állapot')
                ->formatStateUsing(fn($state) => $this->formatStatus($state))
                ->color(fn($state) => $this->getStatusColor($state))
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
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->recordTitle(fn(TeamEvent $record) => $this->formatType($record->type))
            ->columns($this->getTableColumns())
            ->actions($this->getTableActions())
            ->defaultSort('created_at', 'desc')
            ->poll('5s');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('type')
                ->label('Típus')
                ->formatStateUsing(fn($state) => $this->formatType($state))
                ->sortable(),
            Tables\Columns\TextColumn::make('scope')
                ->label('Kezdeményező')
                ->weight(FontWeight::Bold)
                ->formatStateUsing(fn($state) => $this->formatScope($state))
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->label('Állapot')
                ->formatStateUsing(fn($state) => $this->formatStatus($state))
                ->color(fn($state) => $this->getStatusColor($state))
                ->badge(),
            Tables\Columns\TextColumn::make('response.status')
                ->label('Válasz állapota')
                ->formatStateUsing(fn($state) => $this->formatResponseStatus($state))
                ->color(fn($state) => $this->getResponseStatusColor($state))
                ->default('invalid')
                ->badge(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Létrehozva')
                ->date()
                ->since()
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\Action::make('response')
                ->label('Válaszadás')
                ->icon('heroicon-o-chat-bubble-oval-left')
                ->color('secondary')
                ->hidden(fn(TeamEvent $teamEvent) => $this->shouldHideResponseAction($teamEvent))
                ->form([
                    MarkdownEditor::make('message')
                        ->label('Üzenet')
                        ->required(),
                ])
                ->action(fn(array $data, TeamEvent $record) => $this->handleResponseAction($data, $record)),
        ];
    }

    protected function formatType($state): string
    {
        return match ($state) {
            TeamEventType::AmendRequest => 'Módosítási kérvény',
            TeamEventType::Approval => 'Elfogadás',
        };
    }

    protected function formatScope($state): string
    {
        return match ($state) {
            TeamEventScope::School => 'Iskola menedzser',
            TeamEventScope::Organizer => 'Szervező',
        };
    }

    protected function formatStatus($state): string
    {
        return match ($state) {
            TeamEventStatus::Pending => 'Folyamatban',
            TeamEventStatus::Approved => 'Elfogadva',
            TeamEventStatus::Rejected => 'Elutasítva',
        };
    }

    protected function getStatusColor($state): string
    {
        return match ($state) {
            TeamEventStatus::Pending => 'warning',
            TeamEventStatus::Approved => 'success',
            TeamEventStatus::Rejected => 'danger',
        };
    }

    protected function formatResponseStatus($state): string
    {
        return match ($state) {
            TeamEventResponseStatus::Pending => 'Folyamatban',
            TeamEventResponseStatus::Approved => 'Elfogadva',
            TeamEventResponseStatus::Rejected => 'Elutasítva',
            'invalid' => 'Nem értelmezhető',
        };
    }

    protected function getResponseStatusColor($state): string
    {
        return match ($state) {
            TeamEventResponseStatus::Pending => 'warning',
            TeamEventResponseStatus::Approved => 'success',
            TeamEventResponseStatus::Rejected => 'danger',
            'invalid' => 'primary',
        };
    }

    protected function shouldHideResponseAction(TeamEvent $teamEvent): bool
    {
        return $teamEvent->type !== TeamEventType::AmendRequest ||
            $teamEvent->status !== TeamEventStatus::Pending ||
            $teamEvent->response !== null;
    }

    protected function handleResponseAction(array $data, TeamEvent $record): void
    {
        $model = TeamEventResponse::create([
            'team_event_id' => $record->id,
            'message' => $data['message'],
            'status' => TeamEventResponseStatus::Pending,
            'changes' => [],
        ]);

        Notification::make()
            ->title('Válasz sikeresen elküldve')
            ->success()
            ->send();
    }
}
