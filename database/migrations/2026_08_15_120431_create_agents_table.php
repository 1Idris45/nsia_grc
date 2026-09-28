<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_util')
                ->constrained('utilisateurs')
                ->onDelete('cascade')
                ->comment('Lien vers la table utilisateurs (spécialisation)');
            $table->string('mat_agt')->unique()->comment('Matricule agent');
            $table->enum('type_agent', ['general', 'specifique'])
                ->comment('Général = tous services / Spécifique = rattaché à un seul service');
            $table->foreignId('id_serv')
                ->nullable()
                ->constrained('services')
                ->onDelete('set null')
                ->comment('NULL si Agent Général, obligatoire si Agent Spécifique');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};