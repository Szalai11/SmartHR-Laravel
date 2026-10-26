@extends('layouts.app')

@section('title', 'Aperçu attestation')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-secondary">
                <i class="fas fa-file-pdf text-primary mr-2"></i>
                Aperçu de l'attestation de travail
            </h1>
            <div class="flex gap-3">
                <a href="{{ route('pdf.attestation', $employe->id) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition flex items-center gap-2">
                    <i class="fas fa-download"></i> Télécharger PDF
                </a>
                <a href="{{ route('employes.show', $employe->id) }}" class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                    Retour
                </a>
            </div>
        </div>
        <div class="p-6">
            <iframe src="{{ route('pdf.attestation', $employe->id) }}" class="w-full h-[80vh]" style="border: none;"></iframe>
        </div>
    </div>
</div>
@endsection