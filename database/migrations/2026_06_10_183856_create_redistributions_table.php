<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redistributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('don_id')->constrained('dons')->onDelete('cascade');
            $table->foreignId('daara_id')->constrained('daaras')->onDelete('cascade');
            $table->double('montant');
            $table->date('date_redistribution');
            $table->text('motif')->nullable();
            $table->enum('statut', ['planifie', 'valide', 'effectue'])->default('planifie');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redistributions');
    }
};
