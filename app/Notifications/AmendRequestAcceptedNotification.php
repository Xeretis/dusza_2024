<?php

namespace App\Notifications;

use App\Models\CompetitorProfile;
use App\Models\TeamEvent;
use Filament\Notifications\Notification as NotificationsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AmendRequestAcceptedNotification extends Notification implements
    ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public readonly TeamEvent $event)
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
        if ($notifiable instanceof CompetitorProfile) {
            return ['mail'];
        }
        return ['mail', 'broadcast', 'database'];
    }

    private function renderText(): string
    {
        return 'A "' .
            $this->event->team->name .
            '" csapat hiánypótlási kérelme elfogadásra került. Jó versenyzést kívánunk!';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Dusza verseny - Hiánypótlási kérelem elfogadva')
            ->greeting('Helló!')
            ->line($this->renderText())
            ->action('Tovább az oldalra', route('filament.common.home'));
    }

    public function toBroadcast(): BroadcastMessage
    {
        return NotificationsNotification::make()
            ->title('Hiánypótlási kérelem elfogadva')
            ->body($this->renderText())
            ->getBroadcastMessage();
    }

    public function toDatabase(object $notifiable): array
    {
        return NotificationsNotification::make()
            ->title('Hiánypótlási kérelem elfogadva')
            ->body($this->renderText())
            ->getDatabaseMessage();
    }
}
