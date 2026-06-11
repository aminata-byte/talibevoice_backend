<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talibes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daara_id')->constrained('daaras')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('users')->onDelete('set null')->nullable();
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'sorti'])->default('actif');
            $table->boolean('a_etat_civil')->default(false);
            $table->string('niveau_etude')->nullable();
            $table->boolean('est_majeur')->default(false);
            $table->enum('statut_insertion', ['non_concerne', 'en_attente', 'en_cours', 'insere'])->default('non_concerne');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talibes');
    }
};
