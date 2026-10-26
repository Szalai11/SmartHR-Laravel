<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // VALIDATIONS AVEC RÈGLES FORTES
        $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/u', // Interdit les chiffres
            ],
            'prenom' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/u', // Interdit les chiffres
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
        ], [
            'nom.regex' => 'Le nom ne doit contenir que des lettres, espaces ou tirets (pas de chiffres).',
            'prenom.regex' => 'Le prénom ne doit contenir que des lettres, espaces ou tirets (pas de chiffres).',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.regex' => 'Le mot de passe doit contenir : une majuscule, une minuscule, un chiffre et un caractère spécial (@$!%*?&).',
            'email.unique' => 'Cet email est déjà utilisé.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
        ]);

        // Récupérer le rôle "Candidat" (ID 5 par défaut)
        $roleCandidat = \App\Models\Role::where('nom', 'Candidat')->first();
        $roleId = $roleCandidat ? $roleCandidat->id : 5;

        $user = User::create([
            'name' => $request->prenom . ' ' . $request->nom, // 👈 Fusion nom + prénom
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}