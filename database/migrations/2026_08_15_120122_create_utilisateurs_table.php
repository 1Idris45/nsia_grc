<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom_util');
            $table->string('prenom_util');
            $table->string('tel_util')->nullable();
            $table->string('adres_util')->nullable();
            $table->string('email_util')->unique();
            $table->string('password');
            $table->enum('role', ['client', 'agent'])->comment('Discriminant pour la spécialisation Client/Agent');
            $table->rememberToken();
            $table->timestamps();
        });

        // Table technique requise par Laravel pour les sessions en base
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('utilisateurs');
    }
};