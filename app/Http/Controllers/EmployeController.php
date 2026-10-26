<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\User;
use App\Models\Contrat;
use App\Models\CarriereHistorique;
use Illuminate\Http\Request;

class EmployeController extends Controller
{
    public function index()
    {
        $employes = Employe::with('user')->get();
        return view('employes.index', compact('employes'));
    }

    public function show($id)
    {
        $employe = Employe::with('user', 'contrats', 'historiqueCarriere')->findOrFail($id);
        return view('employes.show', compact('employe'));
    }

    public function edit($id)
    {
        $employe = Employe::findOrFail($id);
        return view('employes.edit', compact('employe'));
    }

    public function update(Request $request, $id)
    {
        $employe = Employe::findOrFail($id);
        
        $validated = $request->validate([
            'poste_actuel' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'solde_conge' => 'required|numeric|min:0',
        ]);

        // 🔥 ENREGISTRER L'HISTORIQUE POUR LE POSTE (si changement)
        if ($employe->poste_actuel != $request->poste_actuel) {
            CarriereHistorique::create([
                'employe_id' => $employe->id,
                'type' => 'poste',
                'ancienne_valeur' => $employe->poste_actuel,
                'nouvelle_valeur' => $request->poste_actuel,
                'date_changement' => now(),
            ]);
        }

        $employe->update($validated);

        return redirect()->route('employes.show', $employe->id)
            ->with('success', 'Employé mis à jour avec succès');
    }

    public function contrats($id)
    {
        $employe = Employe::findOrFail($id);
        $contrats = $employe->contrats;
        return view('employes.contrats', compact('employe', 'contrats'));
    }
}