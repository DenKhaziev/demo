<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccessActivatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Открыт доступ в личный кабинет ученика')
            ->greeting('Добрый день!') 
            ->line('Вы можете войти в личный кабинет ученика и начать прохождение промежуточной аттестации')
            ->line('[Перейдите по ссылке и выберите вкладку "Вход для ученика"](https://new.so-penaty.ru/login)')
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
