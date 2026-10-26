@extends('layouts.app')

@section('title', 'Modifier une offre')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b px-6 py-4">
            <h1 class="text-xl font-bold text-secondary">
                <i class="fas fa-edit text-primary mr-2"></i>
                Modifier l'offre
            </h1>
        </div>

        <form action="{{ route('offres.update', $offre->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                <input type="text" name="titre" value="{{ old('titre', $offre->titre) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                @error('titre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                <textarea name="description" rows="6"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>{{ old('description', $offre->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                <input type="text" name="lieu" value="{{ old('lieu', $offre->lieu) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                @error('lieu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type de contrat *</label>
                <select name="type_contrat" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    <option value="CDI" {{ old('type_contrat', $offre->type_contrat) == 'CDI' ? 'selected' : '' }}>CDI</option>
                    <option value="CDD" {{ old('type_contrat', $offre->type_contrat) == 'CDD' ? 'selected' : '' }}>CDD</option>
                    <option value="Stage" {{ old('type_contrat', $offre->type_contrat) == 'Stage' ? 'selected' : '' }}>Stage</option>
                </select>
                @error('type_contrat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="publiee" {{ old('statut', $offre->statut) == 'publiee' ? 'selected' : '' }}>Publiée</option>
                    <option value="en_attente" {{ old('statut', $offre->statut) == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="fermee" {{ old('statut', $offre->statut) == 'fermee' ? 'selected' : '' }}>Fermée</option>
                </select>
                @error('statut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('offres.admin') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover">
                    <i class="fas fa-save mr-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection