@extends('layouts.app')

@section('title', 'Offres d\'emploi')

@section('content')
<div class="space-y-6">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-secondary">Offres d'emploi</h1>
        <p class="text-gray-500 mt-2">Trouvez l'opportunité qui vous correspond</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($offres as $offre)
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $offre->titre }}</h2>
                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                        {{ $offre->type_contrat }}
                    </span>
                </div>
                <div class="flex items-center text-gray-500 text-sm space-x-4 mt-2">
                    <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $offre->lieu ?? 'Lomé, Togo' }}</span>
                    <span><i class="fas fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($offre->date_publication)->format('d/m/Y') }}</span>
                </div>
                <p class="text-gray-600 text-sm mt-4 line-clamp-3">{{ Str::limit($offre->description, 150) }}</p>
                <a href="{{ route('offres.show', $offre->id) }}" class="mt-4 inline-block w-full text-center bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition">
                    <i class="fas fa-paper-plane mr-2"></i>Postuler
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-white rounded-xl">
            <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucune offre disponible pour le moment</p>
        </div>
        @endforelse
    </div>
</div>
@endsection