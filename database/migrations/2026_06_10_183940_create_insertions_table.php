<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insertions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talibe_id')->constrained('talibes')->onDelete('cascade');
            $table->foreignId('partenaire_id')->constrained('partenaires')->onDelete('cascade');
            $table->foreignId('formation_id')->constrained('formations')->onDelete('set null')->nullable();
            $table->enum('type', ['emploi', 'stage', 'formation']);
            $table->string('poste')->nullable();
            $table->text('description')->nullable();
            $table->string('lieu')->nullable();
            $table->string('type_contrat')->nullable();
            $table->date('date_insertion')->nullable();
            $table->date('date_cloture')->nullable();
            $table->enum('statut', ['en_attente', 'valide', 'en_cours', 'cloture'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insertions');
    }
};
