<?php

namespace App\Mail;

use App\Models\Reclamation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangementStatutMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reclamation $reclamation) {}

    public function build()
    {
        return $this->subject("Mise à jour de votre réclamation #{$this->reclamation->cod_reclam}")
            ->view('emails.changement_statut');
    }
}