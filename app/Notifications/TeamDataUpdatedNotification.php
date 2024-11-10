<?php

namespace App\Notifications;

use App\Models\Team;
use App\Models\User;
use Filament\Notifications\Notification as NotificationsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamDataUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public readonly Team $team)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'broadcast', 'database'];
    }

    private function renderText(): string
    {
        return 'A "' .
            $this->team->name .
            '" csapat adatai frissültek. Kérjük, hogy ellenőrizd ezeket.';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Dusza verseny - Adatváltozás')
            ->greeting('Helló!')
            ->line($this->renderText())
            ->action(
                'Csapat megnyitása',
                route(
                    'filament.organizer.resources.teams.view',
                    $this->team->id
                )
            );
    }

    public function toBroadcast(): BroadcastMessage
    {
        return NotificationsNotification::make()
            ->title('Adatváltozás')
            ->body($this->renderText())
            ->getBroadcastMessage();
    }

    public function toDatabase(object $notifiable): array
    {
        return NotificationsNotification::make()
            ->title('Adatváltozás')
            ->body($this->renderText())
            ->getDatabaseMessage();
    }
}
