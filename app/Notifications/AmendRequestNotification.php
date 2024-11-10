<?php

namespace App\Notifications;

use App\Models\CompetitorProfile;
use App\Models\TeamEvent;
use App\Models\User;
use Filament\Notifications\Notification as NotificationsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Broadcast;

class AmendRequestNotification extends Notification implements ShouldQueue
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
            '" csapat részére hiánypótlási kérelem érkezett. Kérjük, hogy a csapatod tagjai ellenőrizzék a kérelmet.';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Dusza verseny - Hiánypótlási kérelem')
            ->greeting('Helló!')
            ->line($this->renderText())
            ->action('Tovább az oldalra', route('filament.common.home'));
    }

    public function toBroadcast(): BroadcastMessage
    {
        return NotificationsNotification::make()
            ->title('Hiánypótlási kérelem')
            ->body($this->renderText())
            ->getBroadcastMessage();
    }

    public function toDatabase(User $notifiable): array
    {
        return NotificationsNotification::make()
            ->title('Hiánypótlási kérelem')
            ->body($this->renderText())
            ->getDatabaseMessage();
    }
}
