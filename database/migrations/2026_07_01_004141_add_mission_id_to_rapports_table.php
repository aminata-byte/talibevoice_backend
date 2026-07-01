<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->foreignId('mission_id')->nullable()->after('agent_id')
                ->constrained('missions')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('mission_id');
        });
    }
};
