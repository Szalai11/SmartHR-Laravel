@extends('layouts.app')

@section('title', 'Gestion des offres')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-secondary">
                <i class="fas fa-briefcase text-primary mr-2"></i>
                Gestion des offres
            </h1>
            <p class="text-gray-500 text-sm mt-1">Gérez les offres d'emploi et suivez les candidatures</p>
        </div>
        <a href="{{ route('offres.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition flex items-center gap-2">
            <i class="fas fa-plus text-sm"></i>
            Nouvelle offre
        </a>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total offres</p>
                    <p class="text-2xl font-bold text-secondary">{{ $offres->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-briefcase text-primary"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Offres publiées</p>
                    <p class="text-2xl font-bold text-success">{{ $offres->where('statut', 'publiee')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-eye text-success"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Offres fermées</p>
                    <p class="text-2xl font-bold text-gray-500">{{ $offres->where('statut', 'fermee')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-archive text-gray-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total candidatures</p>
                    <p class="text-2xl font-bold text-primary">{{ $offres->sum(function($o) { return $o->candidatures->count(); }) }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des offres -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidatures</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($offres as $offre)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $offre->titre }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($offre->description, 60) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $offre->type_contrat == 'CDI' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $offre->type_contrat == 'CDD' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $offre->type_contrat == 'Stage' ? 'bg-purple-100 text-purple-800' : '' }}">
                                {{ $offre->type_contrat }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($offre->date_publication)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                <i class="fas fa-users mr-1"></i> 
                                {{ $offre->candidatures->count() }} candidat(s)
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $offre->statut == 'publiee' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $offre->statut == 'fermee' ? 'bg-gray-100 text-gray-700' : '' }}
                                {{ $offre->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                <i class="fas {{ $offre->statut == 'publiee' ? 'fa-eye' : ($offre->statut == 'fermee' ? 'fa-archive' : 'fa-clock') }} mr-1"></i>
                                {{ ucfirst($offre->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <!-- Voir les candidatures -->
                                <a href="{{ route('candidatures.admin', ['offre_id' => $offre->id]) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Voir les candidatures">
                                    <i class="fas fa-users text-sm"></i>
                                </a>
                                
                                <!-- Classement des candidats -->
                                <a href="{{ route('candidatures.classement', $offre->id) }}" 
                                   class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Classement des candidats">
                                    <i class="fas fa-chart-line text-sm"></i>
                                </a>
                                
                                <!-- Modifier -->
                                <a href="{{ route('offres.edit', $offre->id) }}" 
                                   class="p-2 text-primary hover:bg-primary/10 rounded-lg transition" title="Modifier">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                
                                <!-- Fermer l'offre -->
                                @if($offre->statut == 'publiee')
                                <form action="{{ route('offres.close', $offre->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition" title="Fermer l'offre" onclick="return confirm('Fermer cette offre ?')">
                                        <i class="fas fa-lock text-sm"></i>
                                    </button>
                                </form>
                                @endif
                                
                                <!-- Supprimer -->
                                <form action="{{ route('offres.destroy', $offre->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer" onclick="return confirm('Supprimer définitivement cette offre ?')">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Si aucune offre -->
        @if($offres->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-briefcase text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucune offre d'emploi pour le moment</p>
            <a href="{{ route('offres.create') }}" class="inline-block mt-4 text-primary hover:underline">
                Créer la première offre
            </a>
        </div>
        @endif
    </div>
</div>
@endsection