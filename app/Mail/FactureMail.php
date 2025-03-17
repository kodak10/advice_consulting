<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class FactureMail extends Mailable
{
    use Queueable, SerializesModels;

    public $facture;
    public $pdfPath;
    public $clientEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($facture, $pdfPath, $clientEmail)
    {
        $this->facture = $facture;
        $this->pdfPath = $pdfPath;
        $this->clientEmail = $clientEmail;
    }

    public function build()
    {
        // Récupérer l'email et le nom de l'utilisateur connecté
        $creatorEmail = Auth::user()->email;
        $creatorName = Auth::user()->name;

        // Envoi de l'email
        return $this->from($creatorEmail, $creatorName) // Expéditeur dynamique
                    ->to($this->clientEmail) // Destinataire obligatoire
                    ->subject("Votre facture #" . $this->facture->id)
                    ->view('frontend.pdf.facture')
                    ->attach($this->pdfPath, [
                        'as' => 'facture.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Facture',
        );
    }
}
