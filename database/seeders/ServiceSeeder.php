<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['lib_serv' => 'Service Sinistres', 'des_serv' => 'Traite les contestations d\'indemnisation et délais de remboursement'],
            ['lib_serv' => 'Service Souscription', 'des_serv' => 'Traite les contestations de résiliation/renouvellement de police'],
            ['lib_serv' => 'Service Comptabilité', 'des_serv' => 'Traite les erreurs de montants prélevés et facturation'],
            ['lib_serv' => 'Service Digital', 'des_serv' => 'Traite les problèmes d\'espace client / application mobile'],
            ['lib_serv' => 'Service Relation Client', 'des_serv' => 'Traite les réclamations d\'accueil et service client'],
            ['lib_serv' => 'Service Sécurité', 'des_serv' => 'Traite les cas de fraude et sécurité'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}