<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id('cod_reclam');
            $table->string('obj_reclam')->comment('Objet court de la réclamation');
            $table->text('des_reclam')->comment('Description détaillée');
            $table->date('dat_reclam')->comment('Date de soumission');
            $table->date('date_echeance')->comment('dat_reclam + 10 jours ouvrés, calculée à la création');
            $table->date('date_traitement')->nullable()->comment('Date de résolution effective, pour les stats de délai');
            $table->boolean('courrier_depassement_envoye')->default(false)->comment('Flag pour éviter les envois multiples du courrier de dépassement');

            $table->foreignId('id_clit')
                ->constrained('clients')
                ->onDelete('cascade')
                ->comment('Client qui soumet (relation Soumettre)');

            $table->foreignId('id_agt')
                ->nullable()
                ->constrained('agents')
                ->onDelete('set null')
                ->comment('Agent qui traite (relation Traiter) - nullable tant que non affecté');

            $table->foreignId('id_stat')
                ->constrained('statuts_reclamation')
                ->comment('Statut courant');

            $table->foreignId('id_typ_rec')
                ->constrained('types_reclamation')
                ->comment('Type de réclamation (relation Correspondre)');

            $table->foreignId('id_serv')
                ->nullable()
                ->constrained('services')
                ->onDelete('set null')
                ->comment('Service affecté automatiquement selon le type');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};