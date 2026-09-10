<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPasswordNotification extends ResetPassword
{
    /**
     * The base ResetPassword notification points at the customer-facing
     * "password.reset" route, which this app doesn't register. Point it at
     * the admin guard's own reset route instead.
     */
    protected function resetUrl($notifiable)
    {
        return url(route('admin.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }

    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(config('app.name').' Admin — Reset Your Password')
            ->line('You are receiving this email because we received a password reset request for your admin account.')
            ->action('Reset Password', $url)
            ->line('This password reset link will expire in '.config('auth.passwords.admins.expire').' minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}
