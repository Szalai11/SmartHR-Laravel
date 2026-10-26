@extends('layouts.app')

@section('title', 'Détails employé')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-secondary">
            <i class="fas fa-user-circle text-primary mr-2"></i>
            Fiche employé
        </h1>
        <a href="{{ route('employes.index') }}" class="text-gray-600 hover:text-primary">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne info personnelle -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-primary-hover px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-primary text-3xl"></i>
                    </div>
                    <div class="text-white">
                        <p class="text-sm opacity-90">Matricule</p>
                        <p class="text-xl font-bold">{{ $employe->matricule }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Nom complet</p>
                    <p class="font-semibold">{{ $employe->prenom }} {{ $employe->nom }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Poste</p>
                    <p>{{ $employe->poste_actuel }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Service</p>
                    <p>{{ $employe->service }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Date d'embauche</p>
                    <p>{{ \Carbon\Carbon::parse($employe->date_embauche)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Solde congés</p>
                    <p class="text-2xl font-bold text-primary">{{ $employe->solde_conge }} jours</p>
                </div>
            </div>
        </div>

        <!-- Colonne contrats -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-2">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h2 class="font-semibold text-secondary">
                    <i class="fas fa-file-signature text-primary mr-2"></i>
                    Historique des contrats
                </h2>
                <a href="{{ route('contrats.create', ['employe_id' => $employe->id]) }}" class="bg-primary text-white px-3 py-1 rounded-lg text-sm hover:bg-primary-hover">
                    <i class="fas fa-plus mr-1"></i> Ajouter contrat
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Poste</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Début</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Fin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Salaire</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($employe->contrats as $contrat)
                        <tr>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    {{ $contrat->type == 'CDI' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $contrat->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $contrat->poste }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $contrat->date_fin ? \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') : 'En cours' }}</td>
                            <td class="px-6 py-4">{{ number_format($contrat->salaire_base, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Aucun contrat enregistré
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Historique carrière -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b px-6 py-4">
            <h2 class="font-semibold text-secondary">
                <i class="fas fa-chart-line text-primary mr-2"></i>
                Historique de carrière
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Ancienne valeur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Nouvelle valeur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($employe->historiqueCarriere as $historique)
                    <tr>
                        <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($historique->date_changement)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs 
                                {{ $historique->type == 'poste' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                {{ ucfirst($historique->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $historique->ancienne_valeur }}</td>
                        <td class="px-6 py-4 text-sm font-medium">{{ $historique->nouvelle_valeur }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Aucun historique de carrière
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        <a href="{{ route('pdf.apercu_attestation', $employe->id) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-file-pdf mr-2"></i>Attestation PDF
        </a>
        <a href="{{ route('pdf.apercu_fiche_prise_fonction', $employe->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
    Fiche de prise de fonction
</a>
        <a href="{{ route('employes.edit', $employe->id) }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover">
            <i class="fas fa-edit mr-2"></i>Modifier
        </a>
    </div>
</div>
@endsection