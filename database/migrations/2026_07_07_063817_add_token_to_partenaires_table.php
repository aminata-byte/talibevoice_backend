<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partenaires', function (Blueprint $table) {
            $table->string('token')->nullable()->after('statut');
        });
    }

    public function down(): void
    {
        Schema::table('partenaires', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
};
