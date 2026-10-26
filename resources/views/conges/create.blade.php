@extends('layouts.app')

@section('title', 'Nouvelle demande de congé')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b px-6 py-4">
            <h1 class="text-xl font-bold text-secondary">
                <i class="fas fa-plus-circle text-primary mr-2"></i>
                Nouvelle demande de congé
            </h1>
        </div>

        <form action="{{ route('conges.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="px-6 py-4 space-y-4">
                <!-- Infos employé -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Votre solde actuel</p>
                            <p class="text-2xl font-bold text-primary">{{ $employe->solde_conge }} jours</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date d'embauche</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($employe->date_embauche)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Type de demande -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de demande *</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="type" value="congé" class="mr-2" required>
                            <span>Congé payé</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="permission" class="mr-2" required>
                            <span>Permission</span>
                        </label>
                    </div>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de début *</label>
                        <input type="date" name="date_debut" value="{{ old('date_debut') }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        @error('date_debut')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin *</label>
                        <input type="date" name="date_fin" value="{{ old('date_fin') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        @error('date_fin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Motif -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motif</label>
                    <textarea name="motif" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                              placeholder="Précisez le motif de votre demande...">{{ old('motif') }}</textarea>
                    @error('motif')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Justificatif (obligatoire pour permission) -->
                <div id="justificatifGroup" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Justificatif * 
                        <span class="text-xs text-gray-500">(PDF, DOC - max 2MB)</span>
                    </label>
                    <input type="file" name="justificatif" accept=".pdf,.doc,.docx"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <p class="text-xs text-orange-600 mt-1">⚠️ Un justificatif est obligatoire pour une permission</p>
                    @error('justificatif')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rappel règles -->
                <div class="bg-blue-50 rounded-lg p-4 text-sm text-blue-800">
                    <p class="font-semibold mb-2"><i class="fas fa-info-circle mr-2"></i>Règles à respecter :</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Ancienneté minimale requise : 6 mois</li>
                        <li>30 jours de congés maximum par an</li>
                        <li>Calcul en jours ouvrables (lundi-vendredi)</li>
                        <li>Justificatif obligatoire pour toute permission</li>
                        <li>Demande > 2 jours nécessite validation RH</li>
                    </ul>
                </div>
            </div>

            <div class="border-t px-6 py-4 flex justify-end space-x-3">
                <a href="{{ route('conges.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover">
                    <i class="fas fa-paper-plane mr-2"></i>Envoyer la demande
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Afficher/cacher le champ justificatif selon le type
    const radioCongé = document.querySelector('input[value="congé"]');
    const radioPermission = document.querySelector('input[value="permission"]');
    const justificatifGroup = document.getElementById('justificatifGroup');

    function toggleJustificatif() {
        if (radioPermission && radioPermission.checked) {
            justificatifGroup.style.display = 'block';
        } else {
            justificatifGroup.style.display = 'none';
        }
    }

    if (radioCongé && radioPermission) {
        radioCongé.addEventListener('change', toggleJustificatif);
        radioPermission.addEventListener('change', toggleJustificatif);
        toggleJustificatif();
    }
</script>
@endsection