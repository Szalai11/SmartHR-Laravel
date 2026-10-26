<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_publication',
        'lieu',
        'type_contrat',
        'statut'
    ];

    // Relation avec Candidatures
    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

    // Relation avec EtapesRecrutement
    public function etapes()
    {
        return $this->hasMany(EtapeRecrutement::class);
    }
}