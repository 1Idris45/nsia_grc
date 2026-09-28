<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_reclamation', function (Blueprint $table) {
            $table->id();
            $table->string('lib_typ_rec')->comment('Ex: Contestation indemnisation, Erreur facturation...');
            $table->string('des_typ_rec')->nullable();
            $table->foreignId('id_serv')
                ->nullable()
                ->constrained('services')
                ->onDelete('set null')
                ->comment('Service par défaut pour l\'affectation automatique selon ce type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_reclamation');
    }
};