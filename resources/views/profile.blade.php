@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-secondary">
            <i class="fas fa-user-circle text-primary mr-2"></i>
            Mon profil
        </h1>
        <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-sm">
            <i class="fas fa-tag mr-1"></i> {{ $user->role->nom ?? 'Candidat' }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne gauche : Informations personnelles -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Carte informations personnelles -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b px-6 py-4 bg-gray-50">
                    <h2 class="font-semibold text-secondary">
                        <i class="fas fa-id-card text-primary mr-2"></i>
                        Informations personnelles
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 uppercase">Nom</label>
                            @php
                                $nameParts = explode(' ', $user->name, 2);
                                $prenom = $nameParts[0] ?? '';
                                $nom = $nameParts[1] ?? '';
                            @endphp
                            <p class="font-medium text-gray-900 mt-1">{{ $nom ?: 'Non défini' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 uppercase">Prénom</label>
                            <p class="font-medium text-gray-900 mt-1">{{ $prenom ?: 'Non défini' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 uppercase">Email</label>
                        <p class="font-medium text-gray-900 mt-1">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 uppercase">Rôle</label>
                        <p class="font-medium text-gray-900 mt-1">{{ $user->role->nom ?? 'Candidat' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 uppercase">Membre depuis</label>
                        <p class="font-medium text-gray-900 mt-1">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Carte modification mot de passe -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b px-6 py-4 bg-gray-50">
                    <h2 class="font-semibold text-secondary">
                        <i class="fas fa-lock text-primary mr-2"></i>
                        Changer mon mot de passe
                    </h2>
                </div>
                <form action="{{ route('profile.update-password') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Mot de passe actuel -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="current_password" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-primary">
                            <button type="button" onclick="togglePassword('current_password', 'eyeIconCurrent')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-primary">
                                <i class="fas fa-eye" id="eyeIconCurrent"></i>
                            </button>
                        </div>
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nouveau mot de passe -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-primary">
                            <button type="button" onclick="togglePassword('password', 'eyeIconNew')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-primary">
                                <i class="fas fa-eye" id="eyeIconNew"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Min 8 caractères : 1 maj, 1 min, 1 chiffre, 1 caractère spécial (@$!%*?&)</p>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Confirmer le mot de passe -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-primary">
                            <button type="button" onclick="togglePassword('password_confirmation', 'eyeIconConfirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-primary">
                                <i class="fas fa-eye" id="eyeIconConfirm"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover">
                        <i class="fas fa-save mr-2"></i> Modifier le mot de passe
                    </button>
                </form>
            </div>
        </div>

        <!-- Colonne droite : Carte employé (si l'utilisateur est un employé) -->
        <div class="space-y-6">
            @if($employe)
            <div class="bg-gradient-to-r from-primary/10 to-primary/5 rounded-xl p-6 border border-primary/20">
                <div class="text-center">
                    <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user text-white text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-gray-800">{{ $employe->prenom }} {{ $employe->nom }}</h3>
                    <p class="text-sm text-gray-500">{{ $employe->poste_actuel ?? $employe->poste ?? 'Poste non défini' }}</p>
                    <p class="text-xs text-gray-400 mt-1">Matricule : {{ $employe->matricule }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-primary/20">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Service :</span>
                        <span class="font-medium">{{ $employe->service ?? 'Non défini' }}</span>
                    </div>
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-600">Date d'embauche :</span>
                        <span class="font-medium">{{ $employe->date_embauche ? \Carbon\Carbon::parse($employe->date_embauche)->format('d/m/Y') : 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-600">Solde congés :</span>
                        <span class="font-medium text-primary">{{ $employe->solde_conge }} jours</span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('employes.show', $employe->id) }}" class="text-sm text-primary hover:underline">
                        Voir ma fiche employé →
                    </a>
                </div>
            </div>
            @endif

            <!-- Informations complémentaires -->
            <div class="bg-gray-50 rounded-xl p-5 text-center">
                <i class="fas fa-shield-alt text-gray-400 text-3xl mb-2"></i>
                <p class="text-xs text-gray-500">
                    Compte sécurisé<br>
                    Dernière connexion : {{ $user->updated_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection