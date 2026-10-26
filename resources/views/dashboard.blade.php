@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-secondary">
            <i class="fas fa-chart-line text-primary mr-2"></i>
            Tableau de bord
        </h1>
        <p class="text-gray-500">
            Bonjour, <span class="font-semibold">{{ $user->name }}</span>
        </p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Carte Employés -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Employés</p>
                    <p class="text-2xl font-bold text-secondary">{{ $stats['total_employes'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-primary text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte Candidatures -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Candidatures en cours</p>
                    <p class="text-2xl font-bold text-secondary">{{ $stats['candidatures_en_cours'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-warning text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte Alertes fin contrat -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Fin contrat (30j)</p>
                    <p class="text-2xl font-bold {{ $stats['fin_contrat_proches'] > 0 ? 'text-danger' : 'text-secondary' }}">
                        {{ $stats['fin_contrat_proches'] }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-danger text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte Congés -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Congés en attente</p>
                    <p class="text-2xl font-bold text-secondary">{{ $stats['conges_en_attente'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-umbrella-beach text-success text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ALERTES FIN DE CONTRAT (détaillées) -->
    @if($alertesContrats->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
        <div class="border-b border-orange-100 px-6 py-4 bg-orange-50">
            <h2 class="text-lg font-semibold text-orange-800">
                <i class="fas fa-exclamation-triangle text-orange-600 mr-2"></i>
                ⚠️ Contrats se terminant dans les 30 jours
            </h2>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($alertesContrats as $contrat)
            <div class="px-6 py-4 flex justify-between items-center hover:bg-orange-50 transition">
                <div>
                    <p class="font-medium text-gray-900">
                        {{ $contrat->employe->prenom ?? '' }} {{ $contrat->employe->nom ?? '' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ $contrat->type }} - {{ $contrat->poste }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-orange-700">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Fin le {{ \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Dans {{ \Carbon\Carbon::now()->diffInDays($contrat->date_fin) }} jours
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="border-t border-orange-100 px-6 py-3 bg-orange-50">
            <a href="{{ route('employes.index') }}" class="text-sm text-orange-700 hover:underline">
                Voir tous les employés →
            </a>
        </div>
    </div>
    @endif

    <!-- Contenu selon le rôle -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-secondary mb-4">
                <i class="fas fa-star text-primary mr-2"></i>
                Vos accès
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @if($role === 'Admin')
                    <div class="bg-gray-50 rounded-lg p-4">
                        <i class="fas fa-cog text-primary text-xl mb-2"></i>
                        <p class="font-medium">Administrateur</p>
                        <p class="text-sm text-gray-500">Accès complet au système</p>
                    </div>
                @endif

                @if(in_array($role, ['Admin', 'RH']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <i class="fas fa-users text-primary text-xl mb-2"></i>
                        <p class="font-medium">Gestion RH</p>
                        <p class="text-sm text-gray-500">Gérer les employés et recrutements</p>
                    </div>
                @endif

                @if(in_array($role, ['Admin', 'RH', 'Chef']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <i class="fas fa-check-circle text-primary text-xl mb-2"></i>
                        <p class="font-medium">Validation congés</p>
                        <p class="text-sm text-gray-500">Valider les demandes</p>
                    </div>
                @endif

                <div class="bg-gray-50 rounded-lg p-4">
                    <i class="fas fa-umbrella-beach text-primary text-xl mb-2"></i>
                    <p class="font-medium">Gestion congés</p>
                    <p class="text-sm text-gray-500">Demander et consulter vos congés</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection