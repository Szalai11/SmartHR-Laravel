@extends('layouts.app')

@section('title', 'Liste des employés')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-secondary">
            <i class="fas fa-users text-primary mr-2"></i>
            Liste des employés
        </h1>
        <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition">
            <i class="fas fa-plus mr-2"></i>Ajouter un employé
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matricule</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom complet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poste</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Solde congés</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($employes as $employe)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-secondary">{{ $employe->matricule }}</td>
                    <td class="px-6 py-4 text-sm">{{ $employe->prenom }} {{ $employe->nom }}</td>
                    <td class="px-6 py-4 text-sm">{{ $employe->poste_actuel }}</td>
                    <td class="px-6 py-4 text-sm">{{ $employe->service }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                            {{ $employe->solde_conge }} jours
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('employes.show', $employe->id) }}" class="text-primary hover:underline mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('employes.edit', $employe->id) }}" class="text-warning hover:underline">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection