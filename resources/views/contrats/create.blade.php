@extends('layouts.app')

@section('title', 'Ajouter un contrat')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b px-6 py-4">
            <h1 class="text-xl font-bold text-secondary">
                <i class="fas fa-file-signature text-primary mr-2"></i>
                Nouveau contrat pour {{ $employe->prenom }} {{ $employe->nom }}
            </h1>
        </div>

        <form action="{{ route('contrats.store') }}" method="POST">
            @csrf
            <input type="hidden" name="employe_id" value="{{ $employe->id }}">

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de contrat *</label>
                    <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="CDI">CDI (Contrat à Durée Indéterminée)</option>
                        <option value="CDD">CDD (Contrat à Durée Déterminée)</option>
                        <option value="Stage">Stage</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Poste *</label>
                    <input type="text" name="poste" value="{{ old('poste') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    @error('poste') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début *</label>
                    <input type="date" name="date_debut" value="{{ old('date_debut') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    @error('date_debut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="date" name="date_fin" value="{{ old('date_fin') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <p class="text-xs text-gray-500 mt-1">Laisser vide pour un CDI</p>
                    @error('date_fin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Salaire de base * (FCFA)</label>
                    <input type="number" step="1000" name="salaire_base" value="{{ old('salaire_base') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    @error('salaire_base') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="border-t px-6 py-4 flex justify-end space-x-3">
                <a href="{{ route('employes.show', $employe->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection