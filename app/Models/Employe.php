<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule',
        'nom',
        'prenom',
        'poste_actuel',  // ⚠️ Attention : c'est poste_actuel, pas poste
        'service',
        'date_embauche',
        'solde_conge',
        'photo'
    ];

    // Relation avec User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec Contrats
    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    // Relation avec Conges
    public function conges()
    {
        return $this->hasMany(Conge::class);
    }

    // Relation avec Historique carrière
    public function historiqueCarriere()
    {
        return $this->hasMany(CarriereHistorique::class);
    }
}