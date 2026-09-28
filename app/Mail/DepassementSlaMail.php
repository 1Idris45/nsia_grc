<?php

namespace App\Mail;

use App\Models\Reclamation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepassementSlaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reclamation $reclamation) {}

    public function build()
    {
        return $this->subject("Votre réclamation #{$this->reclamation->cod_reclam} est toujours en cours de traitement")
            ->view('emails.depassement_sla');
    }
}