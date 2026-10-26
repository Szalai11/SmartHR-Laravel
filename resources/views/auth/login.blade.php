@extends('layouts.app')

@section('title', 'Connexion - SmartHR RH')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-primary-hover px-6 py-8 text-center">
            <i class="fas fa-brain text-white text-4xl mb-2"></i>
            <h1 class="text-2xl font-bold text-white">SmartHR</h1>
            <p class="text-white/80 text-sm mt-1">Espace Ressources Humaines</p>
        </div>

        <div class="p-8">
            <h2 class="text-2xl font-bold text-secondary text-center mb-6">Connexion</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope text-primary mr-1"></i> Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="exemple@email.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4 relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-primary mr-1"></i> Mot de passe
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-primary">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-primary hover:underline">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-primary text-white py-2 rounded-lg hover:bg-primary-hover transition font-medium">
                    <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" class="text-primary hover:underline font-medium">Créer un compte</a>
                </p>
            </div>

            <div class="mt-6 pt-4 border-t text-center">
                <p class="text-xs text-gray-400">
                    <i class="fas fa-shield-alt mr-1"></i> Accès sécurisé
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (password.type === 'password') {
            password.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection