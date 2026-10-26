<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidature_id',
        'etape_recrutement_id',
        'note',
        'commentaire',
        'evaluateur_id'
    ];

    public function candidature()
    {
        return $this->belongsTo(Candidature::class);
    }

    public function etapeRecrutement()
    {
        return $this->belongsTo(EtapeRecrutement::class);
    }

    public function evaluateur()
    {
        return $this->belongsTo(User::class, 'evaluateur_id');
    }
}