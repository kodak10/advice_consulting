<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class DevisMail extends Mailable
{
    use Queueable, SerializesModels;

    public $devis;
    public $pdfPathDevis;
    public $pdfPathFacture;
    public $subject;
    public $body;

    /**
     * Create a new message instance.
     */
    public function __construct($devis, $pdfPathDevis, $pdfPathFacture, $subject, $body)
    {
        $this->devis = $devis;
        $this->pdfPathDevis = $pdfPathDevis;
        $this->pdfPathFacture = $pdfPathFacture;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function build()
{
    // Récupérer l'email et le nom de l'utilisateur connecté
    $creatorEmail = Auth::user()->email; // Récupère l'email de l'utilisateur authentifié
    $creatorName = Auth::user()->name;   // Récupère le nom de l'utilisateur authentifié

    // Récupérer les informations de la banque depuis le devis
    $banque = $this->devis->banque; // Vous pouvez ajuster cela selon la structure de vos données

    // Envoi de l'email
    return $this->from($creatorEmail, $creatorName) // Expéditeur dynamique : email de l'utilisateur connecté
                ->to($this->devis->client->email) // Destinataire : l'email du client
                ->cc('comptable@example.com', 'directeur@example.com') // Ajouter les emails en copie
                ->subject($this->subject) // Sujet dynamique
                ->view('frontend.pdf.devis2') // Vue du message
                ->with([
                    'body' => $this->body,
                    'banque' => $banque, // Passer la variable banque à la vue
                ])
                ->attach($this->pdfPathDevis, [
                    'as' => 'devis.pdf',
                    'mime' => 'application/pdf',
                ]);
}

}
