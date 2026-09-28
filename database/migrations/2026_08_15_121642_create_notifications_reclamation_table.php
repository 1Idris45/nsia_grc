<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications_reclamation', function (Blueprint $table) {
            $table->id('cod_notif');
            $table->foreignId('cod_reclam')
                ->constrained('reclamations', 'cod_reclam')
                ->onDelete('cascade')
                ->comment('Réclamation concernée (relation Générer)');
            $table->text('mess_notif')->comment('Contenu du message envoyé');
            $table->date('dat_notif');
            $table->enum('canal_notif', ['email', 'sms', 'interne', 'email_interne'])
                ->comment('Canal utilisé - email_interne pour le courrier de dépassement SLA');
            $table->enum('statut_notif', ['envoyee', 'echec', 'en_attente'])
                ->default('en_attente')
                ->comment('Statut d\'envoi, utile pour le suivi et les reprises en cas d\'échec');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_reclamation');
    }
};