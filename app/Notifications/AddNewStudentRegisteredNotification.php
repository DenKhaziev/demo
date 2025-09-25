<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AddNewStudentRegisteredNotification extends Notification
{
    use Queueable;

    protected $login;
    protected $password;
 


    /**
     * Create a new notification instance.
     */
    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
      
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Ученик успешно зарегистрирован')
            ->greeting('Здравствуйте, '.$notifiable->name.'!')
            ->line('Ученик был успешно зарегистрирован в системе.')
            ->line('Логин ученика: ' . $this->login)
            ->line('Пароль ученика: ' . $this->password)
            ->line('Вы можете использовать эти данные для входа в личный кабинет ученика.')
            ->line('Пожалуйста, сохраните данное сообщение, чтобы не потерять доступ ученика!')

            ->salutation('с уважением, ОАНО СОШ "ПЕНАТЫ"');
    }
}
