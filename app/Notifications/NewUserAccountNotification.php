<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserAccountNotification extends Notification
{
    use Queueable;

    public $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Informasi Akun Baru')
            ->greeting('Halo, ' . $notifiable->nama)
            ->line('Akun Anda telah berhasil dibuat oleh Administrator. Berikut adalah kredensial login Anda:')
            ->line('Email: ' . $notifiable->email)
            ->line('Password Sementara: ' . $this->password)
            ->line('Demi keamanan, harap segera login dan perbarui password Anda di menu Edit Profile.')
            ->action('Login Sekarang', route('login'))
            ->line('Terima kasih!');
    }
}