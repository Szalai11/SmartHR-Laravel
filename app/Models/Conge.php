<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    use HasFactory;

    protected $fillable = [
        'employe_id',
        'type',
        'date_debut',
        'date_fin',
        'nombre_jours',
        'justificatif_path',
        'motif',
        'statut',
        'commentaire_refus'
    ];

    // Relation avec Employe
    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }
}