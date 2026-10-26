<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etapes_recrutement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offre_id')->constrained('offres')->onDelete('cascade');
            $table->string('nom_etape'); // Présélection, Test, Entretien, Validation finale
            $table->integer('ordre');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etapes_recrutement');
    }
};