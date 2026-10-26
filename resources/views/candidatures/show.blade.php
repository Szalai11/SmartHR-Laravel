@extends('layouts.app')

@section('title', 'Détail candidature')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-secondary">
                <i class="fas fa-user-circle text-primary mr-2"></i>
                Détail de la candidature
            </h1>
            <a href="{{ route('candidatures.admin') }}" class="text-gray-600 hover:text-primary">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations candidat -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3"><i class="fas fa-user text-primary mr-2"></i>Candidat</h3>
                    <p><strong>Nom :</strong> {{ $candidature->candidat->prenom }} {{ $candidature->candidat->nom }}</p>
                    <p><strong>Email :</strong> {{ $candidature->candidat->email }}</p>
                    <p class="mt-2">
                        <strong>CV :</strong>
                        <a href="{{ Storage::url($candidature->candidat->cv_path) }}" target="_blank" class="text-primary hover:underline">
                            <i class="fas fa-file-pdf"></i> Télécharger
                        </a>
                    </p>
                    @if($candidature->candidat->lettre_path)
                    <p>
                        <strong>Lettre de motivation :</strong>
                        <a href="{{ Storage::url($candidature->candidat->lettre_path) }}" target="_blank" class="text-primary hover:underline">
                            <i class="fas fa-file-pdf"></i> Télécharger
                        </a>
                    </p>
                    @endif
                </div>

                <!-- Informations offre -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3"><i class="fas fa-briefcase text-primary mr-2"></i>Offre</h3>
                    <p><strong>Titre :</strong> {{ $candidature->offre->titre }}</p>
                    <p><strong>Type :</strong> {{ $candidature->offre->type_contrat }}</p>
                    <p><strong>Date candidature :</strong> {{ \Carbon\Carbon::parse($candidature->date_postulation)->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Changement de statut -->
            <div class="mt-6 border-t pt-6">
                <h3 class="font-semibold text-gray-800 mb-3"><i class="fas fa-tasks text-primary mr-2"></i>Suivi de la candidature</h3>
                
                <form action="{{ route('candidatures.statut', $candidature->id) }}" method="POST" class="flex items-center gap-4 flex-wrap">
                    @csrf
                    @method('PUT')
                    
                    <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="en_attente" {{ $candidature->statut == 'en_attente' ? 'selected' : '' }}>📋 En attente</option>
                        <option value="preselectionne" {{ $candidature->statut == 'preselectionne' ? 'selected' : '' }}>✅ Présélectionné</option>
                        <option value="entretien" {{ $candidature->statut == 'entretien' ? 'selected' : '' }}>🎯 Entretien</option>
                        <option value="valide" {{ $candidature->statut == 'valide' ? 'selected' : '' }}>🎉 Validé</option>
                        <option value="refuse" {{ $candidature->statut == 'refuse' ? 'selected' : '' }}>❌ Refusé</option>
                    </select>
                    
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover">
                        <i class="fas fa-save mr-2"></i>Mettre à jour
                    </button>
                </form>
            </div>

            <!-- Section évaluation -->
            <div class="mt-6 border-t pt-6">
                <h3 class="font-semibold text-gray-800 mb-3"><i class="fas fa-star text-primary mr-2"></i>Évaluations</h3>
                
                <form action="{{ route('evaluations.store', $candidature->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Note /20</label>
                            <input type="number" name="note" step="0.5" min="0" max="20" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Étape</label>
                            <select name="etape_recrutement_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">-- Choisir une étape --</option>
                                @foreach($candidature->offre->etapes as $etape)
                                    <option value="{{ $etape->id }}">{{ $etape->nom_etape }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Commentaire</label>
                        <textarea name="commentaire" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                    </div>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover">
                        <i class="fas fa-plus mr-2"></i>Ajouter une évaluation
                    </button>
                </form>

                <!-- Liste des évaluations existantes -->
                @if($candidature->evaluations->count() > 0)
                <div class="mt-4 space-y-2">
                    @foreach($candidature->evaluations as $eval)
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $eval->etapeRecrutement->nom_etape ?? 'Étape non définie' }}</span>
                            <span class="text-primary font-bold">{{ $eval->note }}/20</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $eval->commentaire }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $eval->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endforeach
                    
                    <!-- Moyenne -->
                    <div class="mt-3 p-3 bg-primary/10 rounded-lg">
                        <p class="font-semibold">Moyenne : {{ round($candidature->evaluations->avg('note'), 1) }}/20</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Bouton transformation en employé -->
            @if($candidature->statut === 'valide')
            <div class="mt-6 border-t pt-6">
                <form action="{{ route('candidature.transform', $candidature->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-success text-white px-4 py-3 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-user-plus mr-2"></i>
                        Transformer ce candidat en employé
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection