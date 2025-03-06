<?php
namespace App\Notifications;

use App\Models\Devis;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DevisRefusedNotification extends Notification
{
    protected $devis;

    public function __construct(Devis $devis)
    {
        $this->devis = $devis;
    }

    public function via($notifiable)
    {
        return ['mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Le devis "' . $this->devis->num_proforma . '" a été refusé.')
                    ->action('Voir les détails', url('/devis/' . $this->devis->id))
                    ->line('Merci de votre compréhension.');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Le devis "' . $this->devis->num_proforma . '" a été refusé.',
            'devis_id' => $this->devis->id
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('devis-refused');
    }
}
