<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->id('id_histor');
            $table->foreignId('cod_reclam')
                ->constrained('reclamations', 'cod_reclam')
                ->onDelete('cascade')
                ->comment('Réclamation concernée (relation Enregistrer)');
            $table->foreignId('id_util')
                ->nullable()
                ->constrained('utilisateurs')
                ->onDelete('set null')
                ->comment('Utilisateur ayant réalisé l\'action (agent, ou système si null)');
            $table->string('act_ehistor')->comment('Ex: Création, Changement de statut, Affectation, Envoi courrier dépassement');
            $table->text('dat_act_histor')->nullable()->comment('Détail de l\'action, ex: "Statut passé de En cours à Résolue"');
            $table->timestamp('heure_act_histor')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historiques');
    }
};