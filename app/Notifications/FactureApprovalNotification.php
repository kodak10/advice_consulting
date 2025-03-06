<?php
namespace App\Notifications;

use App\Models\Facture;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Broadcasting\Channel;

class FactureApprovalNotification extends Notification
{
    protected $facture;

    public function __construct(Facture $facture)
    {
        $this->facture = $facture;
    }

    public function via($notifiable)
    {
        return ['mail', 'broadcast']; // Envoie à la fois par mail et par broadcast
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nouvelle Facture à Approuver')
                    ->greeting('Bonjour,')
                    ->line('Une nouvelle facture nécessite votre approbation.')
                    ->line('Numéro de la facture : ' . $this->facture->numero)
                    ->line('Client : ' . $this->facture->devis->client->nom)
                    ->line('Coût total : ' . $this->facture->devis->details->sum('total') . ' ' . $this->facture->devis->devise)
                    ->action('Voir la facture', route('dashboard.factures.create', $this->facture->id))
                    ->line('Merci de prendre une décision sur cette facture.');
    }

    public function toArray($notifiable)
    {
        return [
            'facture_id' => $this->facture->id,
            'devis_num' => $this->facture->devis->num_proforma,
            'client_name' => $this->facture->devis->client->nom,
        ];
    }

    // Diffuser sur un canal privé pour l'utilisateur Daf
    public function broadcastOn()
    {
        return new Channel('facture-approval.' . $this->facture->user_id);
    }

    // Optionnel: personnaliser le nom de l'événement
    public function broadcastAs()
    {
        return 'facture.approve';
    }
}
