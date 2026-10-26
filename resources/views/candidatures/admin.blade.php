@extends('layouts.app')

@section('title', 'Gestion des candidatures')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-secondary">
            <i class="fas fa-users text-primary mr-2"></i>
            Gestion des candidatures
        </h1>
        <div class="flex space-x-3">
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                <i class="fas fa-clock mr-1"></i> En attente: {{ $candidatures->where('statut', 'en_attente')->count() }}
            </span>
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                <i class="fas fa-check-circle mr-1"></i> Validées: {{ $candidatures->where('statut', 'valide')->count() }}
            </span>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('candidatures.admin') }}" class="px-3 py-1 rounded-full text-sm {{ !request('statut') ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600' }}">Toutes</a>
            <a href="{{ route('candidatures.admin', ['statut' => 'en_attente']) }}" class="px-3 py-1 rounded-full text-sm {{ request('statut') == 'en_attente' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600' }}">En attente</a>
            <a href="{{ route('candidatures.admin', ['statut' => 'preselectionne']) }}" class="px-3 py-1 rounded-full text-sm {{ request('statut') == 'preselectionne' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600' }}">Présélectionnés</a>
            <a href="{{ route('candidatures.admin', ['statut' => 'entretien']) }}" class="px-3 py-1 rounded-full text-sm {{ request('statut') == 'entretien' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600' }}">Entretien</a>
            <a href="{{ route('candidatures.admin', ['statut' => 'valide']) }}" class="px-3 py-1 rounded-full text-sm {{ request('statut') == 'valide' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600' }}">Validés</a>
            <a href="{{ route('candidatures.admin', ['statut' => 'refuse']) }}" class="px-3 py-1 rounded-full text-sm {{ request('statut') == 'refuse' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600' }}">Refusés</a>
        </div>
    </div>

    <!-- Tableau des candidatures -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Offre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CV</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($candidatures as $candidature)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $candidature->candidat->prenom }} {{ $candidature->candidat->nom }}</div>
                            <div class="text-sm text-gray-500">{{ $candidature->candidat->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $candidature->offre->titre }}</td>
                        <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($candidature->date_postulation)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ Storage::url($candidature->candidat->cv_path) }}" target="_blank" class="text-primary hover:underline">
                                <i class="fas fa-file-pdf"></i> Voir CV
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statutClass = match($candidature->statut) {
                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                    'preselectionne' => 'bg-blue-100 text-blue-800',
                                    'entretien' => 'bg-purple-100 text-purple-800',
                                    'valide' => 'bg-green-100 text-green-800',
                                    'refuse' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statutText = match($candidature->statut) {
                                    'en_attente' => 'En attente',
                                    'preselectionne' => 'Présélectionné',
                                    'entretien' => 'Entretien',
                                    'valide' => 'Validé',
                                    'refuse' => 'Refusé',
                                    default => $candidature->statut
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $statutClass }}">
                                {{ $statutText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('candidatures.show', $candidature->id) }}" class="text-primary hover:underline mr-3">
                                <i class="fas fa-eye"></i> Détail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 block"></i>
                            Aucune candidature reçue
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection