<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidat_id',
        'offre_id',
        'date_postulation',
        'statut',
        'commentaire_rh'
    ];

    // Relation avec Candidat
    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }

    // Relation avec Offre
    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }

    // Relation avec Evaluations
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    
}