<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailUpdatedNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pemberitahuan Perubahan Email')
            ->greeting('Halo, ' . $notifiable->nama)
            ->line('Kami menginformasikan bahwa alamat email untuk akun Anda telah diperbarui.')
            ->line('Email Anda yang baru terdaftar adalah: ' . $notifiable->email)
            ->line('Jika Anda tidak merasa melakukan perubahan ini, segera hubungi Administrator.')
            ->action('Masuk ke Sistem', route('login'));
    }
}