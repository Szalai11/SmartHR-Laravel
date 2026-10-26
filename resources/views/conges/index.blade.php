@extends('layouts.app')

@section('title', 'Gestion des congés')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <h1 class="text-2xl font-bold text-secondary">
            <i class="fas fa-umbrella-beach text-primary mr-2"></i>
            Gestion des congés
        </h1>
        <a href="{{ route('conges.create') }}" 
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition">
            <i class="fas fa-plus mr-2"></i>Nouvelle demande
        </a>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">En attente</p>
                    <p class="text-2xl font-bold text-warning">
                        {{ $demandes->where('statut', 'en_attente')->count() }}
                    </p>
                </div>
                <i class="fas fa-clock text-warning text-2xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Validées (Chef)</p>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ $demandes->where('statut', 'valide_chef')->count() }}
                    </p>
                </div>
                <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Validées (RH)</p>
                    <p class="text-2xl font-bold text-success">
                        {{ $demandes->where('statut', 'valide_rh')->count() }}
                    </p>
                </div>
                <i class="fas fa-check-double text-success text-2xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Refusées</p>
                    <p class="text-2xl font-bold text-danger">
                        {{ $demandes->where('statut', 'refuse')->count() }}
                    </p>
                </div>
                <i class="fas fa-times-circle text-danger text-2xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Justificatifs</p>
                    <p class="text-2xl font-bold text-purple-600">
                        {{ $demandes->whereNotNull('justificatif_path')->count() }}
                    </p>
                </div>
                <i class="fas fa-file-pdf text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Tableau des demandes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Justificatif</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($demandes as $demande)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium text-gray-900">{{ $demande->employe->prenom ?? '' }} {{ $demande->employe->nom ?? '' }}</div>
                            <div class="text-xs text-gray-500">{{ $demande->employe->matricule ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs 
                                {{ $demande->type == 'congé' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                <i class="fas {{ $demande->type == 'congé' ? 'fa-umbrella-beach' : 'fa-clock' }} mr-1"></i>
                                {{ ucfirst($demande->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div>{{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">→ {{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold">{{ $demande->nombre_jours }} jours</td>
                        <td class="px-6 py-4 text-sm">
                            @if($demande->justificatif_path)
                                <a href="{{ Storage::url($demande->justificatif_path) }}" target="_blank" class="text-primary hover:underline">
                                    <i class="fas fa-file-pdf"></i> Voir
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">Aucun</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $statutClass = match($demande->statut) {
                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                    'valide_chef' => 'bg-blue-100 text-blue-800',
                                    'valide_rh' => 'bg-green-100 text-green-800',
                                    'refuse' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statutIcon = match($demande->statut) {
                                    'en_attente' => 'fa-clock',
                                    'valide_chef' => 'fa-check-circle',
                                    'valide_rh' => 'fa-check-double',
                                    'refuse' => 'fa-times-circle',
                                    default => 'fa-question-circle'
                                };
                                $statutText = match($demande->statut) {
                                    'en_attente' => 'En attente',
                                    'valide_chef' => 'Validé (N1 - Chef)',
                                    'valide_rh' => 'Validé (N2)',
                                    'refuse' => 'Refusé',
                                    default => $demande->statut
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $statutClass }}">
                                <i class="fas {{ $statutIcon }} mr-1"></i>
                                {{ $statutText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                <!-- 🔥 BOUTON POUR LE CHEF (demandes en attente) -->
                                @if($role === 'Chef' && $demande->statut === 'en_attente')
                                    <button onclick="ouvrirModalValidation({{ $demande->id }})" 
                                            class="text-primary hover:text-primary-hover transition" title="Traiter la demande">
                                        <i class="fas fa-check-circle"></i> Traiter
                                    </button>
                                @endif

                                <!-- 🔥 BOUTON POUR RH/ADMIN (demandes validées par Chef) -->
                                @if(in_array($role, ['Admin', 'RH']) && $demande->statut === 'valide_chef')
                                    <button onclick="ouvrirModalValidation({{ $demande->id }})" 
                                            class="text-primary hover:text-primary-hover transition" title="Valider définitivement">
                                        <i class="fas fa-check-double"></i> Valider
                                    </button>
                                @endif

                                <!-- ⏳ INDICATEUR POUR RH/ADMIN (Chef pas encore validé) -->
                                @if(in_array($role, ['Admin', 'RH']) && $demande->statut === 'en_attente')
                                    <span class="text-gray-400 text-xs">⏳ En attente Chef</span>
                                @endif

                                <!-- PDF (uniquement pour les demandes validées) -->
                                @if($demande->statut === 'valide_rh')
                                    <a href="{{ route('pdf.apercu', $demande->id) }}" 
                                       class="text-purple-600 hover:text-purple-800 transition" title="Bon de congé PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-5xl text-gray-300 mb-3 block"></i>
                            <p>Aucune demande de congé pour le moment</p>
                            <a href="{{ route('conges.create') }}" class="inline-block mt-3 text-primary hover:underline">
                                Faire une demande
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de validation -->
<div id="validationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="border-b px-6 py-4">
            <h3 class="text-lg font-semibold text-secondary">
                <i class="fas fa-gavel text-primary mr-2"></i>
                Traiter la demande
            </h3>
        </div>
        <form id="validationForm" method="POST">
            @csrf
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="action" value="valider" class="mr-2" checked>
                            <span class="flex items-center text-success">
                                <i class="fas fa-check-circle mr-1"></i> Valider
                            </span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="action" value="refuser" class="mr-2">
                            <span class="flex items-center text-danger">
                                <i class="fas fa-times-circle mr-1"></i> Refuser
                            </span>
                        </label>
                    </div>
                </div>
                <div id="commentaireGroup" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motif du refus *</label>
                    <textarea name="commentaire" rows="3" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Expliquez la raison du refus..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Ce motif sera communiqué à l'employé</p>
                </div>
            </div>
            <div class="border-t px-6 py-4 flex justify-end space-x-3">
                <button type="button" onclick="fermerModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition">
                    <i class="fas fa-save mr-2"></i> Confirmer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function ouvrirModalValidation(id) {
        const modal = document.getElementById('validationModal');
        const form = document.getElementById('validationForm');
        form.action = "{{ url('conges') }}/" + id + "/valider";
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function fermerModal() {
        const modal = document.getElementById('validationModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        
        // Réinitialiser le formulaire
        const commentaireGroup = document.getElementById('commentaireGroup');
        commentaireGroup.classList.add('hidden');
        document.querySelector('input[value="valider"]').checked = true;
        document.querySelector('textarea[name="commentaire"]').value = '';
    }

    // Gestion de l'affichage du champ commentaire
    document.querySelectorAll('input[name="action"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const commentaireGroup = document.getElementById('commentaireGroup');
            if (this.value === 'refuser') {
                commentaireGroup.classList.remove('hidden');
            } else {
                commentaireGroup.classList.add('hidden');
            }
        });
    });

    // Fermeture en cliquant à l'extérieur
    document.getElementById('validationModal').addEventListener('click', function(e) {
        if (e.target === this) fermerModal();
    });
</script>
@endsection