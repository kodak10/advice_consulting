<?php

namespace App\Notifications;

use App\Models\Devis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;

class DevisCreatedNotification extends Notification
{
    use Queueable;

    public $devis;

    public function __construct(Devis $devis)
    {
        $this->devis = $devis;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Enregistre dans la base et diffuse en temps réel
    }

    /**
     * Notification dans la base de données.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => "Un nouveau devis a été créé : " . $this->devis->id,
            'devis_id' => $this->devis->id,
            'user_id' => $this->devis->user_id, // L'utilisateur associé au devis
            'devis_number' => $this->devis->numero, // Numéro du devis
        ];
    }

    /**
     * Diffusion en temps réel via Pusher.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "Un nouveau devis a été créé : " . $this->devis->id,
            'devis_id' => $this->devis->id,
            'user_id' => $this->devis->user_id,
            'devis_number' => $this->devis->numero,
        ]);
    }

    /**
     * Canal de diffusion.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->devis->user_id);
    }

    /**
     * Événement pour broadcasting.
     */
    public function broadcastAs()
    {
        return 'devis.created';
    }

    /**
     * Représentation par email.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Un nouveau devis a été créé.')
                    ->action('Voir le devis', url('/devis/' . $this->devis->id))
                    ->line('Merci d\'avoir utilisé notre application.');
    }

    /**
     * Représentation sous forme de tableau.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'devis_id' => $this->devis->id,
            'message' => "Un nouveau devis a été créé : " . $this->devis->id,
        ];
    }
}
