<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('daara_id')->constrained('daaras')->onDelete('cascade')->nullable();
            $table->string('titre');
            $table->enum('type', ['recensement', 'suivi', 'distribution', 'autre'])->default('recensement');
            $table->text('contenu');
            $table->enum('statut', ['brouillon', 'soumis', 'valide'])->default('brouillon');
            $table->date('date_creation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
