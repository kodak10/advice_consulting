<?php

namespace App\Notifications;

use App\Models\Devis;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class DevisCreatedNotification extends Notification
{
    use Queueable;

    public $devis;

    public function __construct($devis)
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
   
    public function toDatabase($notifiable)
    {
        return [
            'message' => "Un nouveau devis a été créé",
            'devis_id' => $this->devis->id,
            'user_id' => $this->devis->user_id, 
            'devis_number' => $this->devis->num_proforma,
        ];
    }
  
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "Un nouveau devis a été créé",
            'devis_id' => $this->devis->id,
            'user_id' => $this->devis->user_id, 
            'devis_number' => $this->devis->num_proforma, 
        ]);
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.' . $this->devis->user_id;
    }


    /**
     * Canal de diffusion.
     */
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('user.' . $this->devis->user_id);
    // }

    public function broadcastOn()
    {
        // Diffuse uniquement aux utilisateurs qui ne sont pas le créateur du devis
        $channels = ['user.' . $this->devis->user_id];

        // Ajouter une condition pour exclure l'utilisateur qui a créé le devis
        if ($this->devis->user_id != Auth::id()) {
            return $channels;
        }

        // Si l'utilisateur est le créateur, ne diffuse pas
        return [];
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
            'message' => "Un nouveau devis a été créé"
        ];
    }
}
