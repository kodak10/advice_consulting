<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class DevisApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $devis;
    public $pdfPathDevis;
    public $userName;
    public $clientName;

    /**
     * Create a new message instance.
     */
    public function __construct($devis, $pdfPathDevis, $userName, $clientName)
    {
        $this->devis = $devis;
        $this->pdfPathDevis = $pdfPathDevis;
        $this->userName = $userName;
        $this->clientName = $clientName;

    }

    public function build()
{
    $subject = "Facture Proforma N°" . $this->devis->num_proforma;
    $filename = basename($this->pdfPathDevis); // Extraire le nom du fichier

    return $this->subject($subject)
                ->view('administration.vues.devisEmail')
                ->with([
                    'devis' => $this->devis,
                    'userName' => $this->userName,
                    'clientName' => $this->clientName,
                ])
                ->attach($this->pdfPathDevis, [
                    'as' => 'proforma_' . $filename, // Utiliser le nom de fichier propre
                    'mime' => 'application/pdf',
                ])
                ->from(config('mail.from.address'), config('mail.from.name'))
                ->replyTo('contact@wuras.ci', 'Service Client')
                ->to($this->devis->client->email);


}

    

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Proforma Advice Consulting',
        );
    }

    /**
     * Get the message content definition.
     */
    

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    
}