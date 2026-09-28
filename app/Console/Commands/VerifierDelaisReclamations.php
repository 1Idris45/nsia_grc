<?php

namespace App\Console\Commands;

use App\Models\Reclamation;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class VerifierDelaisReclamations extends Command
{
    protected $signature = 'reclamations:verifier-delais';
    protected $description = "Détecte les réclamations ayant dépassé le délai de 10 jours ouvrés et envoie le courrier de dépassement.";

    public function handle(NotificationService $notificationService): int
    {
        $reclamationsHorsDelai = Reclamation::whereNull('date_traitement')
            ->where('date_echeance', '<', now())
            ->where('courrier_depassement_envoye', false)
            ->get();

        $this->info("{$reclamationsHorsDelai->count()} réclamation(s) hors délai trouvée(s).");

        foreach ($reclamationsHorsDelai as $reclamation) {
            $notificationService->notifierDepassementSla($reclamation);

            \App\Models\Historique::create([
                'cod_reclam' => $reclamation->cod_reclam,
                'id_util' => null, // action système
                'act_ehistor' => 'Envoi courrier dépassement',
                'dat_act_histor' => 'Courrier envoyé automatiquement (délai de 10 jours ouvrés dépassé).',
                'heure_act_histor' => now(),
            ]);

            $this->line("→ Courrier envoyé pour la réclamation #{$reclamation->cod_reclam}");
        }

        return self::SUCCESS;
    }
}