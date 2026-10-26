@extends('layouts.app')

@section('title', $offre->titre)

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Fil d'Ariane -->
    <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('offres.publiques') }}" class="text-gray-500 hover:text-primary transition">
                    <i class="fas fa-home mr-1"></i> Accueil
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                    <a href="{{ route('offres.publiques') }}" class="text-gray-500 hover:text-primary transition">Offres</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                    <span class="text-gray-700 font-medium">{{ Str::limit($offre->titre, 40) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-primary to-primary-hover px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">{{ $offre->titre }}</h1>
                    <div class="flex flex-wrap gap-3 mt-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-white/20 text-white">
                            <i class="fas fa-briefcase mr-1"></i> {{ $offre->type_contrat }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-white/20 text-white">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $offre->lieu ?? 'Lomé, Togo' }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-white/20 text-white">
                            <i class="fas fa-calendar-alt mr-1"></i> Publiée le {{ \Carbon\Carbon::parse($offre->date_publication)->format('d/m/Y') }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="prose max-w-none">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-file-alt text-primary mr-2"></i> Description du poste
                        </h3>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $offre->description }}</p>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h4 class="font-semibold text-gray-800 mb-3">📋 Profil recherché</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start"><i class="fas fa-check-circle text-success mt-0.5 mr-2 text-sm"></i>Expérience pertinente dans le domaine</li>
                            <li class="flex items-start"><i class="fas fa-check-circle text-success mt-0.5 mr-2 text-sm"></i>Bonnes capacités de communication et travail en équipe</li>
                            <li class="flex items-start"><i class="fas fa-check-circle text-success mt-0.5 mr-2 text-sm"></i>Maîtrise des outils et technologies requis</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de candidature -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-paper-plane text-primary mr-2"></i>
                        Postuler à cette offre
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        @auth
                            @if(Auth::user()->role->nom === 'Candidat')
                                Vous êtes connecté en tant que candidat
                            @else
                                Tous les champs sont obligatoires
                            @endif
                        @else
                            Tous les champs sont obligatoires
                        @endauth
                    </p>
                </div>

                <form action="{{ route('candidature.store', $offre->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf

                    @auth
                        @php
                            $user = Auth::user();
                            $isCandidat = $user->role->nom === 'Candidat';
                            $candidat = App\Models\Candidat::where('email', $user->email)->first();
                            // Extraire nom et prénom depuis le champ 'name' de l'utilisateur
                            $nameParts = explode(' ', $user->name, 2);
                            $prenomUser = $nameParts[0] ?? '';
                            $nomUser = $nameParts[1] ?? '';
                        @endphp

                        @if($isCandidat)
                            <!-- Candidat connecté : champs pré-remplis et cachés -->
                            @if($candidat)
                                <input type="hidden" name="nom" value="{{ $candidat->nom }}">
                                <input type="hidden" name="prenom" value="{{ $candidat->prenom }}">
                            @else
                                <input type="hidden" name="nom" value="{{ $nomUser }}">
                                <input type="hidden" name="prenom" value="{{ $prenomUser }}">
                            @endif
                            <input type="hidden" name="email" value="{{ $user->email }}">

                            <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-600">
                                <p><strong>Nom :</strong> {{ $candidat->nom ?? $nomUser }}</p>
                                <p><strong>Prénom :</strong> {{ $candidat->prenom ?? $prenomUser }}</p>
                                <p><strong>Email :</strong> {{ $user->email }}</p>
                            </div>
                        @else
                            <!-- Utilisateur connecté mais pas candidat (Admin, RH, etc.) -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nom *</label>
                                    <input type="text" name="nom" value="{{ old('nom') }}"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                        placeholder="Votre nom" required>
                                    @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="prenom" value="{{ old('prenom') }}"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                        placeholder="Votre prénom" required>
                                    @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                    placeholder="exemple@email.com" required>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif
                    @else
                        <!-- Utilisateur non connecté -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nom *</label>
                                <input type="text" name="nom" value="{{ old('nom') }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                    placeholder="Votre nom" required>
                                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Prénom *</label>
                                <input type="text" name="prenom" value="{{ old('prenom') }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                    placeholder="Votre prénom" required>
                                @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="exemple@email.com" required>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endauth

                    <!-- Champs communs : CV et Lettre -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">CV (PDF) *</label>
                        <input type="file" name="cv" accept=".pdf"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:bg-primary/10 file:text-primary file:text-xs file:font-medium hover:file:bg-primary/20 transition cursor-pointer"
                            required>
                        <p class="text-xs text-gray-400 mt-1">Format PDF, max 2 Mo</p>
                        @error('cv') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Lettre de motivation (optionnel)</label>
                        <input type="file" name="lettre" accept=".pdf"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:bg-primary/10 file:text-primary file:text-xs file:font-medium hover:file:bg-primary/20 transition cursor-pointer">
                        <p class="text-xs text-gray-400 mt-1">Format PDF, max 2 Mo</p>
                        @error('lettre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full bg-primary text-white px-4 py-2.5 rounded-lg hover:bg-primary-hover transition flex items-center justify-center gap-2 font-medium">
                        <i class="fas fa-paper-plane"></i>
                        Envoyer ma candidature
                    </button>
                </form>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-5">
                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle text-primary mr-2"></i>
                    Pourquoi nous rejoindre ?
                </h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start"><i class="fas fa-chart-line text-success mt-0.5 mr-2 text-xs"></i>Évolution professionnelle garantie</li>
                    <li class="flex items-start"><i class="fas fa-users text-success mt-0.5 mr-2 text-xs"></i>Environnement de travail moderne</li>
                    <li class="flex items-start"><i class="fas fa-graduation-cap text-success mt-0.5 mr-2 text-xs"></i>Formations continues</li>
                    <li class="flex items-start"><i class="fas fa-heart text-success mt-0.5 mr-2 text-xs"></i>Avantages sociaux attractifs</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection