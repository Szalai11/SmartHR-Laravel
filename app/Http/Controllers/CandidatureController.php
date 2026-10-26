<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Candidat;
use App\Models\Candidature;
use App\Models\User;
use App\Models\Employe;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\CandidatureRecue;
use App\Mail\NouvelleCandidatureRH;
use App\Mail\StatutCandidatureChange;

class CandidatureController extends Controller
{
    /**
     * Afficher la liste des offres pour les candidats (page d'accueil)
     */
    public function offres()
    {
        $offres = Offre::where('statut', 'publiee')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('candidat.offres', compact('offres'));
    }

    /**
     * Enregistrer une candidature (accessible sans authentification)
     */
    public function store(Request $request, $offreId)
    {
        try {
            $offre = Offre::findOrFail($offreId);

            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email',
                'cv' => 'required|file|mimes:pdf|max:2048',
                'lettre' => 'nullable|file|mimes:pdf|max:2048',
            ]);

            // VÉRIFICATION 1 : Candidat déjà EMPLOYÉ ?
            $estEmploye = Employe::whereHas('user', function($q) use ($request) {
                $q->where('email', $request->email);
            })->exists();

            if ($estEmploye) {
                return back()->with('error', '❌ Vous êtes déjà employé de SmartHR. Vous ne pouvez plus postuler à aucune offre.');
            }

            // VÉRIFICATION 2 : Candidat déjà RETENU (statut = 'retenu' ou 'valide')
            $candidatExistant = Candidat::where('email', $request->email)->first();
            if ($candidatExistant && in_array($candidatExistant->statut, ['retenu', 'valide'])) {
                return back()->with('error', '❌ Vous avez déjà été retenu pour une offre. Vous ne pouvez plus postuler à de nouvelles offres.');
            }

            // VÉRIFICATION 3 : DÉJÀ POSTULÉ À CETTE OFFRE ?
            $existeDeja = Candidature::whereHas('candidat', function($q) use ($request) {
                $q->where('email', $request->email);
            })->where('offre_id', $offreId)->exists();

            if ($existeDeja) {
                return back()->with('warning', '⚠️ Vous avez déjà postulé à cette offre. Votre candidature est en cours d\'examen.');
            }

            $cvPath = $request->file('cv')->store('cvs', 'public');
            $lettrePath = $request->hasFile('lettre') 
                ? $request->file('lettre')->store('lettres', 'public') 
                : null;

            $candidat = Candidat::firstOrCreate(
                ['email' => $request->email],
                [
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'email' => $request->email,
                    'cv_path' => $cvPath,
                    'lettre_path' => $lettrePath,
                    'statut' => 'en_attente',
                    'user_id' => null
                ]
            );

            if (!$candidat->wasRecentlyCreated) {
                $candidat->update([
                    'cv_path' => $cvPath,
                    'lettre_path' => $lettrePath ?? $candidat->lettre_path,
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                ]);
            }

            $candidature = Candidature::create([
                'candidat_id' => $candidat->id,
                'offre_id' => $offreId,
                'date_postulation' => now(),
                'statut' => 'en_attente',
            ]);

            // 🔔 NOTIFICATION POUR TOUS LES ADMIN + RH (EN DEHORS DU TRY EMAIL)
            $usersANotifier = User::whereHas('role', function($q) {
                $q->whereIn('nom', ['Admin', 'RH']);
            })->get();

            foreach ($usersANotifier as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'candidature',
                    'title' => '📌 Nouvelle candidature',
                    'message' => $candidat->prenom . ' ' . $candidat->nom . ' a postulé à ' . $offre->titre,
                    'url' => route('candidatures.show', $candidature->id),
                    'is_read' => false,
                ]);
            }

            // Envoi des emails (avec try/catch séparé)
            try {
                Mail::to($candidat->email)->send(new CandidatureRecue($candidature));

                foreach ($usersANotifier as $user) {
                    Mail::to($user->email)->send(new NouvelleCandidatureRH($candidature));
                }
            } catch (\Exception $e) {
                Log::error('Erreur envoi email: ' . $e->getMessage());
            }

            return redirect()->route('offres')
                ->with('success', '✅ Votre candidature a été envoyée avec succès !');

        } catch (\Exception $e) {
            Log::error('Erreur candidature: ' . $e->getMessage());
            return back()->with('error', '❌ Une erreur technique est survenue. Veuillez réessayer plus tard.');
        }
    }

    /**
     * Liste des candidatures pour l'administration
     */
    public function adminIndex(Request $request)
    {
        $this->authorizeRole(['Admin', 'RH']);
        
        $query = Candidature::with(['candidat', 'offre'])->whereNull('archived_at');
        
        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('offre_id') && $request->offre_id != '') {
            $query->where('offre_id', $request->offre_id);
        }
        
        $candidatures = $query->orderBy('created_at', 'desc')->get();
        $offres = Offre::all();

        return view('candidatures.admin', compact('candidatures', 'offres'));
    }

    /**
     * Afficher le détail d'une candidature
     */
    public function show($id)
    {
        $this->authorizeRole(['Admin', 'RH']);
        
        $candidature = Candidature::with(['candidat', 'offre', 'evaluations'])
            ->findOrFail($id);

        return view('candidatures.show', compact('candidature'));
    }

    /**
     * Mettre à jour le statut d'une candidature
     */
    public function updateStatut(Request $request, $id)
    {
        $this->authorizeRole(['Admin', 'RH']);
        
        $candidature = Candidature::findOrFail($id);
        $ancienStatut = $candidature->statut;

        $validated = $request->validate([
            'statut' => 'required|in:en_attente,preselectionne,entretien,valide,refuse',
        ]);

        $candidature->update($validated);

        if ($validated['statut'] === 'valide') {
            $candidature->candidat->update(['statut' => 'retenu']);
        }

        // 🔔 NOTIFICATION AU CANDIDAT (EMAIL + APPLICATION)
        if ($ancienStatut !== $validated['statut']) {
            try {
                Mail::to($candidature->candidat->email)->send(new StatutCandidatureChange($candidature));
            } catch (\Exception $e) {
                Log::error('Erreur envoi email statut: ' . $e->getMessage());
            }

            // 🔔 NOTIFICATION DANS L'APPLICATION POUR LE CANDIDAT
            $candidatUser = User::where('email', $candidature->candidat->email)->first();
            if ($candidatUser) {
                Notification::create([
                    'user_id' => $candidatUser->id,
                    'type' => 'candidature',
                    'title' => '📢 Statut de votre candidature',
                    'message' => 'Votre candidature pour ' . $candidature->offre->titre . ' est maintenant : ' . ucfirst($validated['statut']),
                    'url' => route('candidatures.mes'),
                    'is_read' => false,
                ]);
            }
        }

        return back()->with('success', 'Statut mis à jour et candidat notifié par email.');
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

    /**
     * Transformer un candidat retenu en employé
     */
    public function transformToEmploye($id)
    {
        $this->authorizeRole(['Admin', 'RH']);
        
        $candidature = Candidature::with(['candidat', 'offre'])->findOrFail($id);
        
        if ($candidature->statut !== 'valide') {
            return back()->with('error', 'Seul un candidat validé peut être transformé en employé');
        }
        
        $candidat = $candidature->candidat;
        
        $existingUser = User::where('email', $candidat->email)->first();
        
        if (!$existingUser) {
            $user = User::create([
                'name' => $candidat->prenom . ' ' . $candidat->nom,
                'email' => $candidat->email,
                'password' => Hash::make('password123'),
                'role_id' => 4,
            ]);
        } else {
            $user = $existingUser;
            $user->update(['role_id' => 4]);
        }
        
        $lastEmploye = Employe::orderBy('id', 'desc')->first();
        $lastNum = $lastEmploye ? intval(substr($lastEmploye->matricule, -3)) : 0;
        $matricule = 'EMP-' . date('Y') . '-' . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
        
        $employe = Employe::updateOrCreate(
            ['user_id' => $user->id],
            [
                'matricule' => $matricule,
                'nom' => $candidat->nom,
                'prenom' => $candidat->prenom,
                'poste_actuel' => $candidature->offre->titre,
                'service' => 'À définir',
                'date_embauche' => now(),
                'solde_conge' => 30,
            ]
        );
        
        return redirect()->route('employes.show', $employe->id)
            ->with('success', 'Candidat transformé en employé avec succès. Matricule : ' . $matricule);
    }

    /**
     * Classement des candidats pour une offre
     */
    public function classement($offreId)
    {
        $this->authorizeRole(['Admin', 'RH']);
        
        $offre = Offre::findOrFail($offreId);
        
        $candidatures = Candidature::where('offre_id', $offreId)
            ->whereNull('archived_at')
            ->with(['candidat', 'evaluations'])
            ->get()
            ->map(function($candidature) {
                $moyenne = $candidature->evaluations->avg('note') ?? 0;
                $candidature->moyenne = round($moyenne, 2);
                return $candidature;
            })
            ->sortByDesc('moyenne');
        
        return view('candidatures.classement', compact('offre', 'candidatures'));
    }

    /**
     * Mes candidatures (pour le candidat connecté)
     */
    public function mesCandidatures()
    {
        $user = Auth::user();
        $candidat = Candidat::where('email', $user->email)->first();
        
        if (!$candidat) {
            return view('candidat.aucune', ['message' => 'Vous n\'avez pas encore postulé']);
        }
        
        $candidatures = Candidature::where('candidat_id', $candidat->id)
            ->with('offre')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('candidat.mes_candidatures', compact('candidatures'));
    }
}