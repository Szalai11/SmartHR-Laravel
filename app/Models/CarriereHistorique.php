<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarriereHistorique extends Model
{
    use HasFactory;

    protected $fillable = [
        'employe_id',
        'type',
        'ancienne_valeur',
        'nouvelle_valeur',
        'date_changement'
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }
}