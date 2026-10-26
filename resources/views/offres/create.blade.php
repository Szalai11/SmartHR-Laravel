@extends('layouts.app')

@section('title', 'Nouvelle offre')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b px-6 py-4">
            <h1 class="text-xl font-bold text-secondary">Créer une offre d'emploi</h1>
        </div>
        <form action="{{ route('offres.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Titre *</label>
                <input type="text" name="titre" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Description *</label>
                <textarea name="description" rows="6" class="w-full border rounded-lg px-3 py-2" required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium">Lieu</label>
                <input type="text" name="lieu" class="w-full border rounded-lg px-3 py-2" placeholder="Lomé, Togo">
            </div>
            <div>
                <label class="block text-sm font-medium">Type de contrat *</label>
                <select name="type_contrat" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="CDI">CDI</option>
                    <option value="CDD">CDD</option>
                    <option value="Stage">Stage</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Statut</label>
                <select name="statut" class="w-full border rounded-lg px-3 py-2">
                    <option value="publiee">Publiée</option>
                    <option value="en_attente">En attente</option>
                    <option value="fermee">Fermée</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('offres.admin') }}" class="px-4 py-2 border rounded-lg">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg">Publier</button>
            </div>
        </form>
    </div>
</div>
@endsection