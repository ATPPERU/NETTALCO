<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;

class CustomResetPasswordNotification extends ResetPassword
{
    use Queueable;

    public $token; // debe ser público como en la clase base

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Recuperación de contraseña')
            ->markdown('emails.password-reset', [
                'url' => $url,
                'user' => $notifiable,
            ]);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
