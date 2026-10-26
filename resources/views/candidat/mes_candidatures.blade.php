@extends('layouts.app')

@section('title', 'Mes candidatures')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-secondary">
            <i class="fas fa-file-alt text-primary mr-2"></i>
            Mes candidatures
        </h1>
        <a href="{{ route('offres.publiques') }}" class="text-primary hover:underline text-sm">
            <i class="fas fa-plus mr-1"></i> Postuler à une nouvelle offre
        </a>
    </div>

    @if($candidatures->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Vous n'avez encore postulé à aucune offre.</p>
            <a href="{{ route('offres.publiques') }}" class="inline-block mt-4 bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition">
                Voir les offres disponibles
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4">
            @foreach($candidatures as $candidature)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="p-6">
                    <div class="flex justify-between items-start flex-wrap gap-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold text-gray-800">{{ $candidature->offre->titre }}</h2>
                            <div class="flex flex-wrap gap-3 mt-1">
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-briefcase mr-1"></i> {{ $candidature->offre->type_contrat }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $candidature->offre->lieu ?? 'Lomé, Togo' }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i> Postulée le {{ \Carbon\Carbon::parse($candidature->date_postulation)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        <div>
                            @php
                                $statutClass = match($candidature->statut) {
                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                    'preselectionne' => 'bg-blue-100 text-blue-800',
                                    'entretien' => 'bg-purple-100 text-purple-800',
                                    'valide' => 'bg-green-100 text-green-800',
                                    'refuse' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statutIcon = match($candidature->statut) {
                                    'en_attente' => 'fa-clock',
                                    'preselectionne' => 'fa-star',
                                    'entretien' => 'fa-calendar-alt',
                                    'valide' => 'fa-check-circle',
                                    'refuse' => 'fa-times-circle',
                                    default => 'fa-question-circle'
                                };
                                $statutText = match($candidature->statut) {
                                    'en_attente' => '📋 En attente',
                                    'preselectionne' => '✅ Présélectionné',
                                    'entretien' => '🎯 Entretien',
                                    'valide' => '🎉 Validé',
                                    'refuse' => '❌ Refusé',
                                    default => ucfirst($candidature->statut)
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statutClass }}">
                                <i class="fas {{ $statutIcon }} mr-1"></i>
                                {{ $statutText }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-gray-600 text-sm">{{ Str::limit($candidature->offre->description, 200) }}</p>
                    </div>

                    @if($candidature->statut === 'entretien')
                        <div class="mt-3 bg-blue-50 rounded-lg p-3 text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Félicitations ! Vous êtes convoqué(e) en entretien. Vous serez contacté(e) pour fixer la date.
                        </div>
                    @elseif($candidature->statut === 'valide')
                        <div class="mt-3 bg-green-50 rounded-lg p-3 text-sm text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Félicitations ! Votre candidature a été retenue. Vous allez recevoir un email avec la suite.
                        </div>
                    @elseif($candidature->statut === 'refuse')
                        <div class="mt-3 bg-red-50 rounded-lg p-3 text-sm text-red-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Nous vous remercions de votre intérêt pour SmartHR. Nous vous souhaitons une bonne continuation.
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Statistiques -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <p class="text-2xl font-bold text-primary">{{ $candidatures->count() }}</p>
                    <p class="text-xs text-gray-500">Total candidatures</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-yellow-600">{{ $candidatures->where('statut', 'en_attente')->count() }}</p>
                    <p class="text-xs text-gray-500">En attente</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-600">{{ $candidatures->where('statut', 'entretien')->count() }}</p>
                    <p class="text-xs text-gray-500">Entretiens</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ $candidatures->where('statut', 'valide')->count() }}</p>
                    <p class="text-xs text-gray-500">Validées</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection