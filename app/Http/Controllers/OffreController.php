<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffreController extends Controller
{
    /**
     * Liste des offres (publiques pour les candidats)
     */
    public function index()
    {
        $offres = Offre::where('statut', 'publiee')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('offres.index', compact('offres'));
    }

    /**
     * Liste des offres pour l'administration (RH/Admin)
     */
    public function adminIndex()
    {
        $offres = Offre::orderBy('created_at', 'desc')->get();
        $user = Auth::user();
        $role = $user->role->nom ?? 'Employe';
        return view('offres.admin', compact('offres', 'role'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $this->authorizeRole(['Admin', 'RH']);
        return view('offres.create');
    }

    /**
     * Enregistrer une offre
     */
    public function store(Request $request)
    {
        $this->authorizeRole(['Admin', 'RH']);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'lieu' => 'nullable|string|max:255',
            'type_contrat' => 'required|in:CDI,CDD,Stage',
            'statut' => 'required|in:publiee,fermee,en_attente',
        ]);

        $validated['date_publication'] = now();

        Offre::create($validated);

        return redirect()->route('offres.admin')
            ->with('success', 'Offre publiée avec succès');
    }

    /**
     * Afficher une offre
     */
    public function show($id)
    {
        $offre = Offre::findOrFail($id);
        return view('offres.show', compact('offre'));
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $this->authorizeRole(['Admin', 'RH']);
        $offre = Offre::findOrFail($id);
        return view('offres.edit', compact('offre'));
    }

    /**
     * Mettre à jour une offre
     */
    public function update(Request $request, $id)
    {
        $this->authorizeRole(['Admin', 'RH']);
        $offre = Offre::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'lieu' => 'nullable|string|max:255',
            'type_contrat' => 'required|in:CDI,CDD,Stage',
            'statut' => 'required|in:publiee,fermee,en_attente',
        ]);

        $offre->update($validated);

        return redirect()->route('offres.admin')
            ->with('success', 'Offre mise à jour');
    }

    /**
     * Fermer/Archiver une offre
     */
    public function close($id)
    {
        $this->authorizeRole(['Admin', 'RH']);
        $offre = Offre::findOrFail($id);
        $offre->update(['statut' => 'fermee']);

        return back()->with('success', 'Offre fermée');
    }

    /**
     * Supprimer une offre
     */
    public function destroy($id)
    {
        $this->authorizeRole(['Admin', 'RH']);
        $offre = Offre::findOrFail($id);
        $offre->delete();

        return redirect()->route('offres.admin')
            ->with('success', 'Offre supprimée');
    }

    /**
     * Vérifier les rôles autorisés
     */
    private function authorizeRole(array $roles)
    {
        $user = Auth::user();
        $userRole = $user->role->nom ?? 'Employe';

        if (!in_array($userRole, $roles)) {
            abort(403, 'Accès non autorisé');
        }
    }
}