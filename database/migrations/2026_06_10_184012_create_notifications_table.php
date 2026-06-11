<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('message');
            $table->enum('type', [
                'don_valide',
                'redistribution',
                'insertion_talibe',
                'besoin_urgent',
                'offre_validee'
            ]);
            $table->enum('destinataire_type', ['agent', 'partenaire']);
            $table->unsignedBigInteger('destinataire_id');
            $table->boolean('est_lue')->default(false);
            $table->date('date_envoi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
