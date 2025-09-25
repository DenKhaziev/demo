<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentPasswordResetLink extends Notification
{
    public $student;
    public $token;

    public function __construct($student, $token)
    {
        $this->student = $student;
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('student.password.reset', [
            'token' => $this->token,
            'email' => $this->student->email, // фейковый email, используется для идентификации
        ], false));

        return (new MailMessage)
            ->subject('Сброс пароля ученика '.$this->student->name)
            ->greeting('Здравствуйте!')
            ->line('Вы получили это письмо, потому что был отправлен запрос на сброс пароля ученика '.$this->student->name.'.')
            ->action('Сбросить пароль', $url)
            ->line('Ссылка действительна 60 минут.')
            ->line('Если вы не отправляли этот запрос, просто проигнорируйте письмо.')
            ->salutation('с уважением, ОАНО СОШ "ПЕНАТЫ"');
    }
}
