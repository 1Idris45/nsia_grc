<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,           // avant TypeReclamationSeeder (dépendance)
            StatutReclamationSeeder::class,
            TypeReclamationSeeder::class,
            UtilisateurSeeder::class,
        ]);
    }
}