<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_util')
                ->constrained('utilisateurs')
                ->onDelete('cascade')
                ->comment('Lien vers la table utilisateurs (spécialisation)');
            $table->string('num_client')->unique()->nullable()->comment('Numéro client/police NSIA si applicable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};