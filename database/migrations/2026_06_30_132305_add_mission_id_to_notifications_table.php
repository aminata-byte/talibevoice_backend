<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('mission_id')->nullable()->after('admin_id')
                ->constrained('missions')->onDelete('cascade');
        });

        // PostgreSQL : on doit recréer la contrainte enum (type) avec la nouvelle valeur
        DB::statement("ALTER TABLE notifications DROP CONSTRAINT notifications_type_check");
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('don_valide','redistribution','insertion_talibe','besoin_urgent','offre_validee','mission_assignee'))");
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('mission_id');
        });

        DB::statement("ALTER TABLE notifications DROP CONSTRAINT notifications_type_check");
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('don_valide','redistribution','insertion_talibe','besoin_urgent','offre_validee'))");
    }
};
