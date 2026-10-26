@extends('layouts.app')

@section('title', 'Inscription - SmartHR RH')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-primary-hover px-6 py-6 text-center">
            <i class="fas fa-brain text-white text-3xl mb-2"></i>
            <h1 class="text-xl font-bold text-white">SmartHR</h1>
            <p class="text-white/80 text-sm">Création de compte</p>
        </div>

        <div class="p-6">
            <h2 class="text-xl font-bold text-secondary text-center mb-5">Inscription</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nom -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Votre nom">
                    @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Prénom -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Votre prénom">
                    @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="exemple@email.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Mot de passe -->
                <div class="mb-3 relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe *</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-primary">
                            <i class="fas fa-eye" id="eyeIcon1"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Min 8 caractères : 1 maj, 1 min, 1 chiffre, 1 caractère spécial (@$!%*?&)</p>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirmer mot de passe -->
                <div class="mb-4 relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe *</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-primary">
                            <i class="fas fa-eye" id="eyeIcon2"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-2 rounded-lg hover:bg-primary-hover transition font-medium">
                    <i class="fas fa-user-plus mr-2"></i> S'inscrire
                </button>
            </form>

            <div class="mt-5 text-center">
                <p class="text-sm text-gray-600">
                    Déjà un compte ?
                    <a href="{{ route('login') }}" class="text-primary hover:underline font-medium">Se connecter</a>
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