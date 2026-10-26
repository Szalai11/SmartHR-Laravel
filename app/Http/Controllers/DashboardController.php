<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employe;
use App\Models\Candidature;
use App\Models\Contrat;
use App\Models\Conge;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 🔥 REDIRECTION : Si c'est un candidat, l'envoyer vers les offres
        if ($user->role->nom === 'Candidat') {
            return redirect()->route('offres.publiques');
        }

        // Récupérer le rôle de l'utilisateur
        $role = $user->role->nom ?? 'Employe';

        // Données communes
        $stats = [
            'total_employes' => Employe::count(),
            'candidatures_en_cours' => Candidature::where('statut', 'en_attente')->count(),
            'fin_contrat_proches' => Contrat::where('date_fin', '<=', now()->addDays(30))
                ->where('date_fin', '>=', now())
                ->count(),
            'conges_en_attente' => Conge::where('statut', 'en_attente')->count(),
        ];

        // Alertes fin de contrat
        $alertesContrats = Contrat::whereNotNull('date_fin')
            ->where('date_fin', '>=', now())
            ->where('date_fin', '<=', now()->addDays(30))
            ->with('employe')
            ->get();

        return view('dashboard', compact('user', 'role', 'stats', 'alertesContrats'));
    }

    public function profil()
    {
        $user = Auth::user();
        $employe = Employe::where('user_id', $user->id)->first();
        return view('profile', compact('user', 'employe'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
        ], [
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.regex' => 'Le mot de passe doit contenir : 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (@$!%*?&).',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect']);
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Mot de passe modifié avec succès');
    }
}