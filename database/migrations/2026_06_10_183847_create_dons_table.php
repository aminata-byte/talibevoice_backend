<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donateur_id')->constrained('donateurs')->onDelete('cascade');
            $table->enum('type', ['financier', 'materiel']);
            $table->double('montant')->nullable();
            $table->string('mode_paiement')->nullable();
            $table->json('items_materiel')->nullable();
            $table->enum('statut', ['en_attente', 'valide', 'rejete'])->default('en_attente');
            $table->date('date_don');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dons');
    }
};
