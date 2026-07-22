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
            $table->string('reference')->nullable()->unique()->after('id');
            $table->string('pay_token')->nullable()->after('mode_paiement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dons', function (Blueprint $table) {
            $table->dropColumn(['reference', 'pay_token']);
        });
    }
};
