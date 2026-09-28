<?php

namespace App\Services;

use Carbon\Carbon;

class SlaService
{
    private const DELAI_JOURS_OUVRES = 10;

    /**
     * Calcule la date d'échéance en ajoutant N jours ouvrés (hors samedi/dimanche)
     * à la date de soumission.
     */
    public function calculerDateEcheance(Carbon $dateDebut): Carbon
    {
        $date = $dateDebut->copy();
        $joursAjoutes = 0;

        while ($joursAjoutes < self::DELAI_JOURS_OUVRES) {
            $date->addDay();
            if (!$date->isWeekend()) {
                $joursAjoutes++;
            }
        }

        return $date;
    }
}