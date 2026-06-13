<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('besoins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daara_id')->constrained('daaras')->onDelete('cascade');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('set null');
            $table->string('type');
            $table->text('description');
            $table->enum('priorite', ['urgent', 'normal', 'faible'])->default('normal');
            $table->enum('statut', ['en_attente', 'en_cours', 'resolu'])->default('en_attente');
            $table->date('date_signalement');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('besoins');
    }
};
