<?php

namespace App\Services;

use App\Models\TypeReclamation;

class AffectationService
{
    /**
     * Détermine le service à affecter automatiquement selon le type de réclamation.
     */
    public function determinerService(TypeReclamation $type): ?int
    {
        return $type->id_serv;
    }
}