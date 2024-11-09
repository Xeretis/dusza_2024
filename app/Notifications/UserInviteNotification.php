<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInviteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $inviteToken)
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
        return ["mail"];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Dusza verseny meghívó')
            ->greeting('Helló!')
            ->line('Meghívtak, hogy regisztrálj a Dusza verseny jelentkezési felületére. Kattints az alábbi gombra, hogy elfogadd:')
            ->action('Meghívó elfogadása', route('accept-invitation', ['token' => $this->inviteToken], absolute: true))
            ->salutation('Üdvözlettel,\nA Dusza verseny csapata');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
