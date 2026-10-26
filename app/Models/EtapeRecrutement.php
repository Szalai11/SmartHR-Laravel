<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapeRecrutement extends Model
{
    use HasFactory;

    protected $table = 'etapes_recrutement';

    protected $fillable = [
        'offre_id',
        'nom_etape',
        'ordre'
    ];

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