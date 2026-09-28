<?php

namespace Database\Seeders;

use App\Models\StatutReclamation;
use Illuminate\Database\Seeder;

class StatutReclamationSeeder extends Seeder
{
    public function run(): void
    {
        $statuts = ['Nouvelle', 'En cours', 'En attente', 'Résolue', 'Rejetée', 'Clôturée'];

        foreach ($statuts as $lib) {
            StatutReclamation::create(['lib_stat' => $lib]);
        }
    }
}