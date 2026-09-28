<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Service;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    public function run(): void
    {
        // Client de test
        $utilClient = Utilisateur::create([
            'nom_util' => 'Kouassi',
            'prenom_util' => 'Awa',
            'tel_util' => '0700000001',
            'email_util' => 'client@test.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);
        Client::create(['id_util' => $utilClient->id, 'num_client' => 'CL-0001']);

        // Agent Général de test
        $utilAgentGeneral = Utilisateur::create([
            'nom_util' => 'Bamba',
            'prenom_util' => 'Ibrahim',
            'tel_util' => '0700000002',
            'email_util' => 'agent.general@test.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);
        Agent::create([
            'id_util' => $utilAgentGeneral->id,
            'mat_agt' => 'AGT-G-0001',
            'type_agent' => 'general',
            'id_serv' => null,
        ]);

        // Agent Spécifique de test
        $serviceSinistres = Service::where('lib_serv', 'Service Sinistres')->first();
        $utilAgentSpecifique = Utilisateur::create([
            'nom_util' => 'Traoré',
            'prenom_util' => 'Fatou',
            'tel_util' => '0700000003',
            'email_util' => 'agent.sinistres@test.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);
        Agent::create([
            'id_util' => $utilAgentSpecifique->id,
            'mat_agt' => 'AGT-S-0001',
            'type_agent' => 'specifique',
            'id_serv' => $serviceSinistres?->id,
        ]);
    }
}