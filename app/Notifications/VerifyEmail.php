<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification
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
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Vérification de votre email')
            ->line('Merci de vous être connecté à votre compte.')
            ->line('Votre adresse e-mail doit être vérifiée pour accéder à toutes les fonctionnalités.')
            ->action('Vérifiez votre adresse e-mail', $this->verificationUrl($notifiable))  // Le lien de vérification
            ->line('Le mot de passe par défaut est : password. Nous vous invitons à le modifier une fois connecté.')
            ->line('Si vous n\'êtes pas à l\'origine de cette connexion, ignorez cet e-mail.');
    }

    /**
     * Génère l'URL de vérification
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',  // Nom de la route
            Carbon::now()->addMinutes(60),  // Lien valide pour 60 minutes
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );
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
