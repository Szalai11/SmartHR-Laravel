<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carriere_historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'poste' ou 'salaire'
            $table->string('ancienne_valeur');
            $table->string('nouvelle_valeur');
            $table->date('date_changement');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carriere_historiques');
    }
};