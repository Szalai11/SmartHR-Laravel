@extends('layouts.app')

@section('title', 'Gestion des contrats')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-secondary">
            <i class="fas fa-file-signature text-primary mr-2"></i>
            Gestion des contrats
        </h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poste</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Début</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Salaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($contrats as $contrat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">{{ $contrat->employe->prenom ?? '' }} {{ $contrat->employe->nom ?? '' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs 
                                {{ $contrat->type == 'CDI' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $contrat->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $contrat->poste }}</td>
                        <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm">{{ $contrat->date_fin ? \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') : 'En cours' }}</td>
                        <td class="px-6 py-4 text-sm">{{ number_format($contrat->salaire_base, 0, ',', ' ') }} FCFA</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('contrats.show', $contrat->id) }}" class="text-primary hover:underline mr-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(in_array($role, ['Admin', 'RH']))
                            <a href="{{ route('contrats.edit', $contrat->id) }}" class="text-warning hover:underline">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Aucun contrat enregistré
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection