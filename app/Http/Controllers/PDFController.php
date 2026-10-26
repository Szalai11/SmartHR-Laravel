<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\Employe;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    /**
     * Générer un bon de congé au format PDF
     */
    public function bonConge($id)
    {
        $conge = Conge::with('employe')->findOrFail($id);
        
        // Vérifier que l'utilisateur a le droit de voir ce congé
        $user = Auth::user();
        $role = $user->role->nom ?? 'Employe';
        $employe = Employe::where('user_id', $user->id)->first();
        
        if (!in_array($role, ['Admin', 'RH']) && ($employe->id ?? 0) != $conge->employe_id) {
            abort(403, 'Accès non autorisé');
        }
        
        // Récupérer le nom du valideur (Chef ou RH)
        $validateur = null;
        if ($conge->statut === 'valide_rh') {
            $validateur = User::whereHas('role', function($q) {
                $q->where('nom', 'RH');
            })->first();
        } elseif ($conge->statut === 'valide_chef') {
            $validateur = User::whereHas('role', function($q) {
                $q->where('nom', 'Chef');
            })->first();
        }
        
        $data = [
            'conge' => $conge,
            'employe' => $conge->employe,
            'validateur' => $validateur,
            'date_generation' => now()->format('d/m/Y'),
        ];
        
        $pdf = Pdf::loadView('pdf.bon_conge', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('bon_conge_' . $conge->employe->matricule . '.pdf');
    }
    
    /**
     * Aperçu du bon de congé avant téléchargement
     */
    public function apercuBonConge($id)
    {
        $conge = Conge::with('employe')->findOrFail($id);
        
        $user = Auth::user();
        $role = $user->role->nom ?? 'Employe';
        $employe = Employe::where('user_id', $user->id)->first();
        
        if (!in_array($role, ['Admin', 'RH']) && ($employe->id ?? 0) != $conge->employe_id) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('pdf.apercu_bon_conge', compact('conge'));
    }

    /**
     * Générer une attestation de travail au format PDF
     */
    public function attestation($id)
    {
        $employe = Employe::with('user', 'contrats')->findOrFail($id);
        
        // Vérifier que l'utilisateur a le droit de voir cette attestation
        $user = Auth::user();
        $role = $user->role->nom ?? 'Employe';
        
        if (!in_array($role, ['Admin', 'RH']) && ($user->id != $employe->user_id)) {
            abort(403, 'Accès non autorisé');
        }
        
        // Le représentant légal est l'entreprise elle-même
        $legalName = 'SmartHR';
        
        $data = [
            'employe' => $employe,
            'contrat' => $employe->contrats()->latest('date_debut')->first(),
            'legal_name' => $legalName,
            'date_generation' => now()->format('d/m/Y'),
        ];
        
        $pdf = Pdf::loadView('pdf.attestation', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('attestation_' . $employe->matricule . '.pdf');
    }

    /**
     * Aperçu de l'attestation avant téléchargement
     */
    public function apercuAttestation($id)
    {
        $employe = Employe::with('user', 'contrats')->findOrFail($id);
        
        $user = Auth::user();
        $role = $user->role->nom ?? 'Employe';
        
        if (!in_array($role, ['Admin', 'RH']) && ($user->id != $employe->user_id)) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('pdf.apercu_attestation', compact('employe'));
    }

    /**
     * Générer la fiche de prise de fonction au format PDF
     */
    public function fichePriseFonction($id)
    {
        $employe = Employe::with('user', 'contrats')->findOrFail($id);

        $user = Auth::user();
        $role = $user->role->nom ?? 'Employe';

        if (!in_array($role, ['Admin', 'RH'])) {
            abort(403, 'Accès non autorisé');
        }

        $data = [
            'employe' => $employe,
            'contrat' => $employe->contrats()->latest('date_debut')->first(),
            'date_generation' => now()->format('d/m/Y'),
        ];

        $pdf = Pdf::loadView('pdf.fiche_prise_fonction', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('fiche_prise_fonction_' . $employe->matricule . '.pdf');
    }

    /**
     * Aperçu de la fiche de prise de fonction avant téléchargement
     */
    public function apercuFichePriseFonction($id)
    {
        $employe = Employe::with('user', 'contrats')->findOrFail($id);

        $user = Auth::user();
        $role = $user->role->nom ?? 'Employe';

        if (!in_array($role, ['Admin', 'RH'])) {
            abort(403, 'Accès non autorisé');
        }

        return view('pdf.apercu_fiche_prise_fonction', compact('employe'));
    }
}