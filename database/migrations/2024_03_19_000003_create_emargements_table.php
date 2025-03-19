<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emargements', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->enum('statut', ['present', 'absent', 'retard']);
            $table->foreignId('professeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emargements');
    }
}; 