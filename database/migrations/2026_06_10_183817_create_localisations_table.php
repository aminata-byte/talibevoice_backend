<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('localisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daara_id')->constrained('daaras')->onDelete('cascade');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('zone')->nullable();
            $table->string('region')->nullable();
            $table->string('commune')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('localisations');
    }
};
