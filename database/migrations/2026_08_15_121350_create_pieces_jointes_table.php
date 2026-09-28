<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pieces_jointes', function (Blueprint $table) {
            $table->id('id_piec');
            $table->foreignId('cod_reclam')
                ->constrained('reclamations', 'cod_reclam')
                ->onDelete('cascade')
                ->comment('Réclamation associée (relation Contenir)');
            $table->string('nom_fich_piec')->comment('Nom original du fichier');
            $table->enum('typ_fich_piec', ['pdf', 'jpg', 'jpeg', 'png'])->comment('Type de fichier');
            $table->string('chem_fichpiec')->comment('Chemin de stockage du fichier');
            $table->unsignedInteger('taille_piec')->comment('Taille en Ko, pour vérifier la limite de 5 Mo');
            $table->timestamp('dat_ajout_piec')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pieces_jointes');
    }
};