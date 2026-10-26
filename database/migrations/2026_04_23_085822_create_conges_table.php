<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
            $table->string('type'); // congé, permission
            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('nombre_jours', 5, 1);
            $table->string('justificatif_path')->nullable();
            $table->text('motif')->nullable();
            $table->enum('statut', ['en_attente', 'valide_chef', 'valide_rh', 'refuse'])->default('en_attente');
            $table->text('commentaire_refus')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conges');
    }
};