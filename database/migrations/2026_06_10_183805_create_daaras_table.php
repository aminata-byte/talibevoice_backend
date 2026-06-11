<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daaras', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('adresse');
            $table->integer('capacite_accueil')->default(0);
            $table->integer('nombre_talibes')->default(0);
            $table->string('nom_responsable');
            $table->string('telephone_responsable')->nullable();
            $table->enum('statut', ['en_attente', 'actif', 'inactif'])->default('en_attente');
            $table->string('region')->nullable();
            $table->string('commune')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daaras');
    }
};