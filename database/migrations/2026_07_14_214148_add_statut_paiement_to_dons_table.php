<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dons', function (Blueprint $table) {
            $table->enum('statut_paiement', ['en_attente', 'paye', 'echoue'])
                ->default('en_attente')
                ->after('mode_paiement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dons', function (Blueprint $table) {
            $table->dropColumn('statut_paiement');
        });
    }
};
