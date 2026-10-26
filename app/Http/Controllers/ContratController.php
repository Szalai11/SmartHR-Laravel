<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Employe;
use App\Models\CarriereHistorique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContratController extends Controller
{
    /**
     * Liste des contrats
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->nom ?? 'Employe';

        if (in_array($role, ['Admin', 'RH'])) {
            $contrats = Contrat::with('employe')->orderBy('created_at', 'desc')->get();
        } else {
            $employe = Employe::where('user_id', $user->id)->first();
            $contrats = Contrat::where('employe_id', $employe->id ?? 0)->get();
        }

        return view('contrats.index', compact('contrats', 'role'));
    }

    /**
     * Formulaire de création
     */
    public function create(Request $request)
    {
        $employe_id = $request->get('employe_id');
        $employe = Employe::findOrFail($employe_id);
        return view('contrats.create', compact('employe'));
    }

    /**
     * Enregistrer un contrat
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'type' => 'required|in:CDI,CDD,Stage',
            'poste' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'salaire_base' => 'required|numeric|min:0',
        ], [
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            'salaire_base.min' => 'Le salaire ne peut pas être négatif.',
        ]);

        // Vérifier si un contrat actif existe déjà
        $contratActif = Contrat::where('employe_id', $request->employe_id)
            ->where(function($q) {
                $q->where('date_fin', '>=', now())
                  ->orWhereNull('date_fin');
            })
            ->first();

        if ($contratActif) {
            return back()->with('error', 'Cet employé a déjà un contrat actif.');
        }

        $contrat = Contrat::create($validated);

        // 🔥 ENREGISTRER L'HISTORIQUE POUR LE SALAIRE
        CarriereHistorique::create([
            'employe_id' => $request->employe_id,
            'type' => 'salaire',
            'ancienne_valeur' => '0 FCFA',
            'nouvelle_valeur' => number_format($request->salaire_base, 0, ',', ' ') . ' FCFA',
            'date_changement' => now(),
        ]);

        return redirect()->route('employes.show', $request->employe_id)
            ->with('success', 'Contrat créé avec succès');
    }

    /**
     * Afficher un contrat
     */
    public function show($id)
    {
        $contrat = Contrat::with('employe')->findOrFail($id);
        return view('contrats.show', compact('contrat'));
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $contrat = Contrat::findOrFail($id);
        $employe = $contrat->employe;
        return view('contrats.edit', compact('contrat', 'employe'));
    }

    /**
     * Mettre à jour un contrat
     */
    public function update(Request $request, $id)
    {
        $contrat = Contrat::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:CDI,CDD,Stage',
            'poste' => 'required|string|max:255',
            'salaire_base' => 'required|numeric|min:0',
            'date_fin' => 'nullable|date|after:date_debut',
        ]);

        // 🔥 ENREGISTRER L'HISTORIQUE SI LE SALAIRE CHANGE
        if ($contrat->salaire_base != $request->salaire_base) {
            CarriereHistorique::create([
                'employe_id' => $contrat->employe_id,
                'type' => 'salaire',
                'ancienne_valeur' => number_format($contrat->salaire_base, 0, ',', ' ') . ' FCFA',
                'nouvelle_valeur' => number_format($request->salaire_base, 0, ',', ' ') . ' FCFA',
                'date_changement' => now(),
            ]);
        }

        $contrat->update($validated);

        return redirect()->route('employes.show', $contrat->employe_id)
            ->with('success', 'Contrat mis à jour');
    }

    /**
     * Supprimer un contrat
     */
    public function destroy($id)
    {
        $contrat = Contrat::findOrFail($id);
        $employe_id = $contrat->employe_id;
        $contrat->delete();

        return redirect()->route('employes.show', $employe_id)
            ->with('success', 'Contrat supprimé');
    }
}