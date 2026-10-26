<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\Employe;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CongeController extends Controller
{
    // Formulaire de demande de congé
    public function create()
    {
        $employe = Employe::where('user_id', Auth::id())->first();
        if (!$employe) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'êtes pas associé à un employé');
        }

        return view('conges.create', compact('employe'));
    }

    // Enregistrer une demande
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_debut' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'date_fin' => [
                'required',
                'date',
                'after_or_equal:date_debut',
            ],
            'type' => [
                'required',
                'string',
                'in:congé,permission',
            ],
            'motif' => [
                'nullable',
                'string',
                'max:500',
            ],
            'justificatif' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048',
            ],
        ], [
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_fin.after_or_equal' => 'La date de fin doit être après la date de début.',
            'type.in' => 'Le type de demande doit être "congé" ou "permission".',
            'motif.max' => 'Le motif ne peut pas dépasser 500 caractères.',
            'justificatif.mimes' => 'Le justificatif doit être au format PDF, JPG ou PNG.',
            'justificatif.max' => 'Le fichier ne doit pas dépasser 2 Mo.',
        ]);

        if ($request->type === 'permission' && !$request->hasFile('justificatif')) {
            return back()->withErrors(['justificatif' => 'Un justificatif est obligatoire pour une permission.'])->withInput();
        }

        $employe = Employe::where('user_id', Auth::id())->first();

        if (!$employe) {
            return back()->with('error', 'Employé non trouvé');
        }

        // 🔥 VÉRIFICATION ANCIENNETÉ (6 mois) UNIQUEMENT POUR LES CONGÉS (pas permissions)
        if ($request->type === 'congé' && $employe->date_embauche > now()->subMonths(6)) {
            return back()->withErrors(['date_debut' => 'Vous devez avoir au moins 6 mois d\'ancienneté pour demander un congé.'])->withInput();
        }

        $jours = $this->calculerJoursOuvrables($request->date_debut, $request->date_fin);

        if ($jours > $employe->solde_conge) {
            return back()->withErrors(['date_fin' => "Solde de congés insuffisant. Il vous reste {$employe->solde_conge} jours. Vous demandez {$jours} jours."])->withInput();
        }

        $congesPrisCetteAnnee = Conge::where('employe_id', $employe->id)
            ->whereYear('date_debut', now()->year)
            ->where('statut', '!=', 'refuse')
            ->sum('nombre_jours');

        if ($congesPrisCetteAnnee + $jours > 30) {
            $reste = 30 - $congesPrisCetteAnnee;
            return back()->withErrors(['date_fin' => "Quota annuel dépassé. Il vous reste {$reste} jours disponibles cette année."])->withInput();
        }

        $justificatifPath = null;
        if ($request->hasFile('justificatif')) {
            $justificatifPath = $request->file('justificatif')->store('justificatifs', 'public');
        }

        $conge = Conge::create([
            'employe_id' => $employe->id,
            'type' => $request->type,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'nombre_jours' => $jours,
            'motif' => $request->motif,
            'justificatif_path' => $justificatifPath,
            'statut' => 'en_attente',
        ]);

        // 🔔 NOTIFICATION POUR LE CHEF, RH ET ADMIN
        $notifTitle = $request->type === 'permission' ? '⏰ Demande de permission' : '🏖️ Demande de congé';
        $notifMessage = $employe->prenom . ' ' . $employe->nom . ' a demandé ' . ($request->type === 'permission' ? 'une permission' : 'un congé');

        // Notifier les Chefs
        $chefs = User::whereHas('role', function($q) {
            $q->where('nom', 'Chef');
        })->get();

        foreach ($chefs as $chef) {
            if ($chef->id != Auth::id()) {
                Notification::create([
                    'user_id' => $chef->id,
                    'type' => 'conge',
                    'title' => $notifTitle,
                    'message' => $notifMessage,
                    'url' => route('conges.index'),
                    'is_read' => false,
                ]);
            }
        }

        // Notifier les RH
        $rhs = User::whereHas('role', function($q) {
            $q->where('nom', 'RH');
        })->get();

        foreach ($rhs as $rh) {
            if ($rh->id != Auth::id()) {
                Notification::create([
                    'user_id' => $rh->id,
                    'type' => 'conge',
                    'title' => $notifTitle . ' (en attente Chef)',
                    'message' => $notifMessage,
                    'url' => route('conges.index'),
                    'is_read' => false,
                ]);
            }
        }

        // Notifier les Admins
        $admins = User::whereHas('role', function($q) {
            $q->where('nom', 'Admin');
        })->get();

        foreach ($admins as $admin) {
            if ($admin->id != Auth::id()) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'conge',
                    'title' => $notifTitle . ' (en attente Chef)',
                    'message' => $notifMessage,
                    'url' => route('conges.index'),
                    'is_read' => false,
                ]);
            }
        }

        return redirect()->route('conges.index')->with('success', 'Votre demande de congé a été envoyée avec succès.');
    }

    // Liste des demandes
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->nom ?? 'Employe';

        if ($role === 'Admin' || $role === 'RH') {
            $demandes = Conge::with('employe')->orderBy('created_at', 'desc')->get();
        } elseif ($role === 'Chef') {
            $demandes = Conge::with('employe')->orderBy('created_at', 'desc')->get();
        } else {
            $employe = Employe::where('user_id', Auth::id())->first();
            $demandes = Conge::where('employe_id', $employe->id ?? 0)->get();
        }

        return view('conges.index', compact('demandes', 'role'));
    }

    // Valider ou refuser une demande
    public function valider($id, Request $request)
    {
        $conge = Conge::findOrFail($id);
        $user = Auth::user();
        $role = $user->role->nom ?? 'Employe';
        $action = $request->input('action');

        $employeConnecte = Employe::where('user_id', $user->id)->first();

        // REFUS
        if ($action === 'refuser') {
            $request->validate([
                'commentaire' => 'required|string|min:3|max:500',
            ], [
                'commentaire.required' => 'Un motif de refus est obligatoire.',
                'commentaire.min' => 'Le motif doit contenir au moins 3 caractères.',
            ]);

            $conge->update([
                'statut' => 'refuse',
                'commentaire_refus' => $request->commentaire,
            ]);

            // 🔔 NOTIFICATION POUR LE CHEF (si le refus vient de RH/Admin)
            if ($role === 'RH' || $role === 'Admin') {
                $chefs = User::whereHas('role', function($q) {
                    $q->where('nom', 'Chef');
                })->get();
                
                foreach ($chefs as $chef) {
                    Notification::create([
                        'user_id' => $chef->id,
                        'type' => 'conge',
                        'title' => '❌ Demande refusée (Niveau 2)',
                        'message' => 'La demande de ' . $conge->employe->prenom . ' ' . $conge->employe->nom . ' a été refusée par ' . $user->name . '. Motif : ' . $request->commentaire,
                        'url' => route('conges.index'),
                        'is_read' => false,
                    ]);
                }
            }

            // 🔔 NOTIFICATION POUR L'EMPLOYÉ
            $employeUser = User::where('id', $conge->employe->user_id)->first();
            if ($employeUser) {
                Notification::create([
                    'user_id' => $employeUser->id,
                    'type' => 'conge',
                    'title' => '❌ Demande refusée',
                    'message' => 'Votre demande a été refusée. Motif : ' . $request->commentaire,
                    'url' => route('conges.index'),
                    'is_read' => false,
                ]);
            }

            return back()->with('success', 'Demande refusée avec succès.');
        }

        // 🔥 VALIDATION

        // Cas 1 : Chef valide (Niveau 1)
        if ($role === 'Chef' && $conge->statut === 'en_attente') {
            if ($employeConnecte && $employeConnecte->id == $conge->employe_id) {
                // Auto-validation Chef → directement validé
                $conge->statut = 'valide_rh';
                $conge->save();
                $employe = $conge->employe;
                $employe->solde_conge -= $conge->nombre_jours;
                $employe->save();
                $message = 'Votre demande a été validée automatiquement.';
            } else {
                $conge->statut = 'valide_chef';
                $conge->save();
                $message = 'Demande validée (Niveau 1 - Chef). En attente de validation RH.';
                
                // 🔔 NOTIFICATION À L'EMPLOYÉ (Validé Niveau 1)
                $employeUser = User::where('id', $conge->employe->user_id)->first();
                if ($employeUser) {
                    Notification::create([
                        'user_id' => $employeUser->id,
                        'type' => 'conge',
                        'title' => '✅ Demande validée (Niveau 1)',
                        'message' => 'Votre demande a été validée par le Chef de service. En attente de validation RH.',
                        'url' => route('conges.index'),
                        'is_read' => false,
                    ]);
                }
            }
        }
        // Cas 2 : RH/Admin valident APRÈS le Chef (Niveau 2)
        elseif (($role === 'RH' || $role === 'Admin') && $conge->statut === 'valide_chef') {
            $conge->statut = 'valide_rh';
            $conge->save();
            $employe = $conge->employe;
            $employe->solde_conge -= $conge->nombre_jours;
            $employe->save();
            $message = 'Demande validée définitivement (Niveau 2). Le solde a été mis à jour.';
            
            // 🔔 NOTIFICATION À L'EMPLOYÉ (Validé Niveau 2)
            $employeUser = User::where('id', $conge->employe->user_id)->first();
            if ($employeUser) {
                Notification::create([
                    'user_id' => $employeUser->id,
                    'type' => 'conge',
                    'title' => '🎉 Demande validée définitivement',
                    'message' => 'Votre demande a été validée par le RH. Votre solde a été mis à jour.',
                    'url' => route('conges.index'),
                    'is_read' => false,
                ]);
            }
        }
        // ❌ Cas 3 : RH/Admin ESSAIENT DE VALIDER SANS LE CHEF
        elseif (($role === 'RH' || $role === 'Admin') && $conge->statut === 'en_attente') {
            return back()->with('error', '❌ La demande doit d\'abord être validée par le Chef de service.');
        }
        // Cas 4 : Erreur
        else {
            return back()->with('error', 'Cette demande ne peut pas être validée dans son état actuel.');
        }

        return back()->with('success', $message);
    }

    /**
     * Calendrier des absences
     */
    public function calendrier()
    {
        $user = Auth::user();
        $role = $user->role->nom ?? 'Employe';
        
        $conges = Conge::whereIn('statut', ['valide_rh', 'valide_chef'])
            ->with('employe')
            ->get()
            ->map(function($conge) {
                return [
                    'title' => $conge->employe->prenom . ' ' . $conge->employe->nom . ' - ' . ucfirst($conge->type),
                    'start' => $conge->date_debut,
                    'end' => date('Y-m-d', strtotime($conge->date_fin . '+1 day')),
                    'backgroundColor' => $conge->type == 'congé' ? '#10B981' : '#F59E0B',
                    'borderColor' => $conge->type == 'congé' ? '#10B981' : '#F59E0B',
                ];
            });
        
        return view('calendrier.index', compact('conges', 'role'));
    }

    /**
     * Calcul des jours ouvrables
     */
    private function calculerJoursOuvrables($dateDebut, $dateFin)
    {
        $debut = new \DateTime($dateDebut);
        $fin = new \DateTime($dateFin);
        $fin->modify('+1 day');

        $jours = 0;
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($debut, $interval, $fin);

        foreach ($period as $date) {
            $jourSemaine = $date->format('N');
            if ($jourSemaine < 6) {
                $jours++;
            }
        }

        return $jours;
    }
}