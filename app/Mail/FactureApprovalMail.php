<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Facture;

class FactureApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $facture;
    public $pdfPath;
    public $approverName;
    public $clientEmail;
    public $clientName;

    public function __construct(Facture $facture, $pdfPath, $approverName, $clientEmail, $clientName)
    {
        $this->facture = $facture;
        $this->pdfPath = $pdfPath;
        $this->approverName = $approverName;
        $this->clientEmail = $clientEmail;
        $this->clientName = $clientName;
    }

    public function build()
    {
        return $this->subject('Votre facture n°' . $this->facture->numero)
                    ->to($this->clientEmail)
                    ->attach($this->pdfPath, [
                        'as' => 'Facture_' . $this->facture->numero . '.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->view('administration.vues.factureEmail');
    }
}