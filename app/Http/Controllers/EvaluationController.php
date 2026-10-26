<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function store(Request $request, $candidatureId)
    {
        // Vérifier que la candidature existe
        $candidature = Candidature::find($candidatureId);
        if (!$candidature) {
            return back()->with('error', 'Candidature non trouvée');
        }

        $request->validate([
            'note' => 'required|numeric|min:0|max:20',
            'etape_recrutement_id' => 'required|exists:etapes_recrutement,id',
            'commentaire' => 'nullable|string',
        ]);

        $evaluation = Evaluation::create([
            'candidature_id' => $candidatureId,
            'etape_recrutement_id' => $request->etape_recrutement_id,
            'note' => $request->note,
            'commentaire' => $request->commentaire,
            'evaluateur_id' => Auth::id(),
        ]);

        return back()->with('success', 'Évaluation ajoutée avec succès');
    }
}