<?php

namespace App\Services;

use App\Models\NotificationReclamation;
use App\Models\Reclamation;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChangementStatutMail;
use App\Mail\DepassementSlaMail;

class NotificationService
{
    public function notifierChangementStatut(Reclamation $reclamation): void
    {
        $client = $reclamation->client->utilisateur;

        $message = "Votre réclamation \"{$reclamation->obj_reclam}\" est passée au statut : {$reclamation->statut->lib_stat}.";

        NotificationReclamation::create([
            'cod_reclam' => $reclamation->cod_reclam,
            'mess_notif' => $message,
            'dat_notif' => now(),
            'canal_notif' => 'email',
            'statut_notif' => 'en_attente',
        ]);

        try {
            Mail::to($client->email_util)->send(new ChangementStatutMail($reclamation));
            $this->marquerNotificationEnvoyee($reclamation);
        } catch (\Exception $e) {
            report($e); // log l'échec, statut reste 'en_attente' pour reprise ultérieure
        }
    }

    public function notifierDepassementSla(Reclamation $reclamation): void
    {
        $client = $reclamation->client->utilisateur;

        $message = "Votre réclamation \"{$reclamation->obj_reclam}\" est toujours en cours de traitement, au-delà du délai habituel de 10 jours ouvrés.";

        NotificationReclamation::create([
            'cod_reclam' => $reclamation->cod_reclam,
            'mess_notif' => $message,
            'dat_notif' => now(),
            'canal_notif' => 'email_interne',
            'statut_notif' => 'en_attente',
        ]);

        try {
            Mail::to($client->email_util)->send(new DepassementSlaMail($reclamation));
            $reclamation->update(['courrier_depassement_envoye' => true]);
        } catch (\Exception $e) {
            report($e);
        }
    }

    private function marquerNotificationEnvoyee(Reclamation $reclamation): void
    {
        NotificationReclamation::where('cod_reclam', $reclamation->cod_reclam)
            ->where('statut_notif', 'en_attente')
            ->latest()
            ->first()
            ?->update(['statut_notif' => 'envoyee']);
    }
}