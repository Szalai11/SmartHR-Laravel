<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email');
            $table->string('cv_path');
            $table->string('lettre_path')->nullable();
            $table->decimal('moyenne_finale', 5, 2)->nullable();
            $table->enum('statut', ['en_attente', 'en_cours', 'refuse', 'retenu'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};