<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\TypeReclamation;
use Illuminate\Database\Seeder;

class TypeReclamationSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['lib' => 'Contestation de montant prélevé', 'service' => 'Service Comptabilité'],
            ['lib' => 'Contestation de résiliation ou renouvellement', 'service' => 'Service Souscription'],
            ['lib' => 'Contestation de montant d\'indemnisation', 'service' => 'Service Sinistres'],
            ['lib' => 'Délai de remboursement', 'service' => 'Service Sinistres'],
            ['lib' => 'Erreur de facturation', 'service' => 'Service Comptabilité'],
            ['lib' => 'Espace client / application mobile', 'service' => 'Service Digital'],
            ['lib' => 'Service client / accueil agence', 'service' => 'Service Relation Client'],
            ['lib' => 'Fraude / sécurité', 'service' => 'Service Sécurité'],
            ['lib' => 'Autre', 'service' => null],
        ];

        foreach ($types as $type) {
            $idServ = $type['service']
                ? Service::where('lib_serv', $type['service'])->first()?->id
                : null;

            TypeReclamation::create([
                'lib_typ_rec' => $type['lib'],
                'id_serv' => $idServ,
            ]);
        }
    }
}