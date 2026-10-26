@extends('layouts.app')

@section('title', 'Classement des candidats - ' . $offre->titre)

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <i class="fas fa-briefcase text-primary"></i>
                <span>{{ $offre->type_contrat }}</span>
                <span class="mx-2">•</span>
                <i class="fas fa-calendar-alt"></i>
                <span>{{ \Carbon\Carbon::parse($offre->date_publication)->format('d/m/Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-secondary">
                {{ $offre->titre }}
            </h1>
            <p class="text-gray-500 mt-1">Classement des candidats par moyenne générale</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('candidatures.admin') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Toutes les candidatures
            </a>
            <a href="{{ route('offres.admin') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                <i class="fas fa-briefcase"></i> Offres
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-gray-500 text-sm">Total candidats</p>
            <p class="text-2xl font-bold text-secondary">{{ $candidatures->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-gray-500 text-sm">Moyenne générale</p>
            <p class="text-2xl font-bold text-primary">{{ round($candidatures->avg('moyenne'), 1) }}/20</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-gray-500 text-sm">Meilleure note</p>
            <p class="text-2xl font-bold text-success">{{ round($candidatures->max('moyenne'), 1) }}/20</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-gray-500 text-sm">Taux de réussite</p>
            <p class="text-2xl font-bold text-warning">
                {{ $candidatures->count() > 0 ? round(($candidatures->where('statut', 'valide')->count() / $candidatures->count()) * 100) : 0 }}%
            </p>
        </div>
    </div>

    <!-- Classement -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-primary/10 to-transparent px-6 py-4 border-b">
            <h2 class="font-semibold text-secondary">
                <i class="fas fa-trophy text-primary mr-2"></i>
                Classement
            </h2>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($candidatures as $index => $candidature)
            <div class="px-6 py-4 hover:bg-gray-50 transition">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Rang et candidat -->
                    <div class="flex items-center gap-4">
                        <div class="w-12 text-center">
                            @if($index === 0)
                                <span class="text-3xl">🥇</span>
                            @elseif($index === 1)
                                <span class="text-3xl">🥈</span>
                            @elseif($index === 2)
                                <span class="text-3xl">🥉</span>
                            @else
                                <span class="text-gray-400 font-bold text-lg">{{ $index + 1 }}e</span>
                            @endif
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">
                                {{ $candidature->candidat->prenom }} {{ $candidature->candidat->nom }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $candidature->candidat->email }}</div>
                        </div>
                    </div>

                    <!-- Moyenne avec barre de progression -->
                    <div class="flex-1 max-w-md">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full transition-all duration-500
                                    {{ $candidature->moyenne >= 15 ? 'bg-success' : ($candidature->moyenne >= 10 ? 'bg-warning' : 'bg-danger') }}"
                                    style="width: {{ ($candidature->moyenne / 20) * 100 }}%">
                                </div>
                            </div>
                            <span class="font-bold text-lg min-w-[60px] text-right
                                {{ $candidature->moyenne >= 15 ? 'text-success' : ($candidature->moyenne >= 10 ? 'text-warning' : 'text-danger') }}">
                                {{ $candidature->moyenne }}/20
                            </span>
                        </div>
                    </div>

                    <!-- Statut et actions -->
                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $candidature->statut == 'valide' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $candidature->statut == 'refuse' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $candidature->statut == 'entretien' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $candidature->statut == 'preselectionne' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $candidature->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                            <i class="fas 
                                {{ $candidature->statut == 'valide' ? 'fa-check-circle' : '' }}
                                {{ $candidature->statut == 'refuse' ? 'fa-times-circle' : '' }}
                                {{ $candidature->statut == 'entretien' ? 'fa-calendar-alt' : '' }}
                                {{ $candidature->statut == 'preselectionne' ? 'fa-star' : '' }}
                                {{ $candidature->statut == 'en_attente' ? 'fa-clock' : '' }} mr-1"></i>
                            {{ ucfirst($candidature->statut) }}
                        </span>
                        <a href="{{ route('candidatures.show', $candidature->id) }}" 
                           class="text-primary hover:underline flex items-center gap-1 text-sm">
                            <i class="fas fa-eye"></i> Détail
                        </a>
                    </div>
                </div>

                <!-- Évaluations récentes (optionnel) -->
                @if($candidature->evaluations->count() > 0)
                <div class="mt-3 ml-16">
                    <div class="flex flex-wrap gap-2">
                        @foreach($candidature->evaluations->take(3) as $eval)
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                            {{ $eval->etape }} : {{ $eval->note }}/20
                        </span>
                        @endforeach
                        @if($candidature->evaluations->count() > 3)
                        <span class="text-xs text-gray-400">+{{ $candidature->evaluations->count() - 3 }}</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <i class="fas fa-users text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Aucune candidature pour cette offre</p>
                <a href="{{ route('offres.publiques') }}" class="inline-block mt-4 text-primary hover:underline">
                    Voir les offres publiées
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Légende -->
    <div class="bg-gray-50 rounded-xl p-4 text-center text-sm text-gray-500">
        <span class="inline-flex items-center mx-2"><span class="w-3 h-3 bg-success rounded-full mr-1"></span> ≥15/20 (Excellent)</span>
        <span class="inline-flex items-center mx-2"><span class="w-3 h-3 bg-warning rounded-full mr-1"></span> 10-15/20 (Bon)</span>
        <span class="inline-flex items-center mx-2"><span class="w-3 h-3 bg-danger rounded-full mr-1"></span> &lt;10/20 (Insuffisant)</span>
    </div>
</div>
@endsection