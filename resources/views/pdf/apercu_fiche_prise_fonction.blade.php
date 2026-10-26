@extends('layouts.app')

@section('title', 'Aperçu fiche de prise de fonction')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold text-secondary">
                <i class="fas fa-file-pdf text-primary mr-2"></i>
                Aperçu de la fiche de prise de fonction
            </h1>
            <a href="{{ route('pdf.fiche_prise_fonction', $employe->id) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition flex items-center gap-2">
                <i class="fas fa-download"></i> Télécharger
            </a>
        </div>
        <iframe src="{{ route('pdf.fiche_prise_fonction', $employe->id) }}" class="w-full h-[80vh]" style="border: none;"></iframe>
    </div>
</div>
@endsection