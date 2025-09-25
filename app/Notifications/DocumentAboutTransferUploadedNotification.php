<?php

namespace App\Notifications;

use App\Models\DocumentByGrade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentAboutTransferUploadedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public DocumentByGrade $document)
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
        ->subject('Справка о зачислении ученика готова и загружена в личный кабинет представителя')
        ->greeting('Добрый день!') 
        ->line('Справка о зачислении по ученику ' . $this->document->child->name . ' загружена.')
        ->line('[Войти в личный кабиент представителя](https://new.so-penaty.ru/parent)')
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
