<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE utilisateurs DROP CONSTRAINT IF EXISTS utilisateurs_role_check");
        DB::statement("ALTER TABLE utilisateurs ADD CONSTRAINT utilisateurs_role_check CHECK (role IN ('client', 'agent', 'admin'))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE utilisateurs DROP CONSTRAINT IF EXISTS utilisateurs_role_check");
        DB::statement("ALTER TABLE utilisateurs ADD CONSTRAINT utilisateurs_role_check CHECK (role IN ('client', 'agent'))");
    }
};