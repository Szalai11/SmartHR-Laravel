@extends('layouts.app')

@section('title', 'Mes candidatures')

@section('content')
<div class="max-w-2xl mx-auto text-center py-12">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
        <h2 class="text-xl font-semibold text-gray-800">Vous n'avez pas encore postulé</h2>
        <p class="text-gray-500 mt-2">Consultez les offres disponibles et soumettez votre première candidature.</p>
        <a href="{{ route('offres.publiques') }}" class="inline-block mt-4 bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition">
            <i class="fas fa-briefcase mr-2"></i> Voir les offres
        </a>
    </div>
</div>
@endsection