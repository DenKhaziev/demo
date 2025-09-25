<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StudentRegisteredNotification extends Notification
{
    use Queueable;

    protected $login;
    protected $password;
    protected $parentLogin;
    protected $parentPassword;


    /**
     * Create a new notification instance.
     */
    public function __construct($login, $password, $parentLogin, $parentPassword)
    {
        $this->login = $login;
        $this->password = $password;
        $this->parentLogin = $parentLogin;
        $this->parentPassword = $parentPassword;
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
            ->subject('Успешная регистрация на образовательной платформе ОАНО СОШ "ПЕНАТЫ"')
            ->greeting('Здравствуйте, '.$notifiable->name.'!')
            ->line('Вы и Ваш ученик были успешно зарегистрированы в системе.')
            ->line('Ваш логин: ' . $this->parentLogin)
            ->line('Ваш пароль: ' . $this->parentPassword)
            ->line('Логин ученика: ' . $this->login)
            ->line('Пароль ученика: ' . $this->password)
            ->line('Вы можете использовать эти данные для входа в личный кабинет представителя и ученика.')
            ->line('Пожалуйста, сохраните данное сообщение, чтобы не потерять данные для входа в личные кабинеты!')
            ->salutation('С уважением, ОАНО СОШ "ПЕНАТЫ"');
    }
}
