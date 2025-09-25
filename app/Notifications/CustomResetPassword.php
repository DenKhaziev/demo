<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    use Queueable;
    public $token;
    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Сброс пароля представителя, самейное образование ОАНО СОШ "ПЕНАТЫ"')
            ->greeting('Здравствуйте!')
            ->line('Вы получили это письмо, потому что был отправлен запрос на сброс пароля.')
            ->action('Сбросить пароль', $resetUrl)
            ->line('Ссылка действительна 60 минут.')
            ->line('Если вы не отправляли запрос, просто проигнорируйте это письмо.')
            ->salutation('с уважением, ОАНО СОШ "ПЕНАТЫ"');
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
