<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
            $table->enum('type', ['CDI', 'CDD', 'Stage']);
            $table->date('date_debut');
            $table->date('date_fin')->nullable(); // Nullable pour CDI
            $table->decimal('salaire_base', 10, 2);
            $table->string('poste');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};