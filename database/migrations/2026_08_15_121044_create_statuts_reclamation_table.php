<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statuts_reclamation', function (Blueprint $table) {
            $table->id();
            $table->string('lib_stat')->comment('Ex: Nouvelle, En cours, En attente, Résolue, Rejetée, Clôturée');
            $table->string('des_stat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statuts_reclamation');
    }
};