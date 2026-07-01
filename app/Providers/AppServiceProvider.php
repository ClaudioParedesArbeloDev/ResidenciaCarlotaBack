<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        //
    }

    
    public function boot(): void
    {
       ResetPassword::toMailUsing(function ($notifiable, $token) {
            $email = urlencode($notifiable->email);
            $url   = "https://claudioparedes.site/carlota/reset-password/{$token}?email={$email}";
 
            return (new MailMessage)
                ->subject('Recuperar contraseña — Residencia Carlota')
                ->greeting('Hola,')
                ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta en Residencia Carlota.')
                ->action('Restablecer contraseña', $url)
                ->line('Este link expira en 60 minutos.')
                ->line('Si no solicitaste esto, podés ignorar este mensaje.');
        });
    }
}
