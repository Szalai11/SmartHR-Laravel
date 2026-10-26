@extends('layouts.app')

@section('title', 'Modifier employé')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b px-6 py-4">
            <h1 class="text-xl font-bold text-secondary">
                <i class="fas fa-edit text-primary mr-2"></i>
                Modifier {{ $employe->prenom }} {{ $employe->nom }}
            </h1>
        </div>

        <form action="{{ route('employes.update', $employe->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Poste</label>
                    <input type="text" name="poste_actuel" value="{{ old('poste_actuel', $employe->poste_actuel) }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                    <input type="text" name="service" value="{{ old('service', $employe->service) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Solde congés (jours)</label>
                    <input type="number" step="0.5" name="solde_conge" value="{{ old('solde_conge', $employe->solde_conge) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
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