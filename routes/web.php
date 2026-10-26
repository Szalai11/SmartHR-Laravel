<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\NotificationController;

// ========== PAGES PUBLIQUES ==========
Route::get('/', [CandidatureController::class, 'offres'])->name('offres');
Route::get('/offres', [OffreController::class, 'index'])->name('offres.publiques');
Route::get('/offres/{id}', [OffreController::class, 'show'])->name('offres.show');
Route::post('/offre/{id}/candidature', [CandidatureController::class, 'store'])->name('candidature.store');

// Authentification
require __DIR__.'/auth.php';

// ========== ROUTES PROTÉGÉES ==========
Route::middleware(['auth'])->group(function () {
    
    Route::get('/calendrier', [CongeController::class, 'calendrier'])->name('calendrier');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [DashboardController::class, 'profil'])->name('profil');
    Route::put('/profil/password', [DashboardController::class, 'updatePassword'])->name('profile.update-password');

    // ========== CANDIDAT (espaces) ==========
    Route::get('/mes-candidatures', [CandidatureController::class, 'mesCandidatures'])->name('candidatures.mes');

    // ========== GESTION DES CONGÉS ==========
    Route::prefix('conges')->group(function () {
        Route::get('/', [CongeController::class, 'index'])->name('conges.index');
        Route::get('/create', [CongeController::class, 'create'])->name('conges.create');
        Route::post('/', [CongeController::class, 'store'])->name('conges.store');
        Route::post('/{id}/valider', [CongeController::class, 'valider'])->name('conges.valider');
    });

    // ========== GESTION DES EMPLOYÉS (RH/Admin) ==========
    Route::middleware(['role:Admin,RH'])->prefix('employes')->group(function () {
        Route::get('/', [EmployeController::class, 'index'])->name('employes.index');
        Route::get('/{id}', [EmployeController::class, 'show'])->name('employes.show');
        Route::get('/{id}/edit', [EmployeController::class, 'edit'])->name('employes.edit');
        Route::put('/{id}', [EmployeController::class, 'update'])->name('employes.update');
        Route::get('/{id}/contrats', [EmployeController::class, 'contrats'])->name('employes.contrats');
    });

    // ========== GESTION DES CONTRATS (RH/Admin) ==========
    Route::middleware(['role:Admin,RH'])->group(function () {
        Route::resource('contrats', ContratController::class);
    });

    // ========== GESTION DES OFFRES (RH/Admin) ==========
    Route::middleware(['role:Admin,RH'])->prefix('admin/offres')->group(function () {
        Route::get('/', [OffreController::class, 'adminIndex'])->name('offres.admin');
        Route::get('/create', [OffreController::class, 'create'])->name('offres.create');
        Route::post('/', [OffreController::class, 'store'])->name('offres.store');
        Route::get('/{id}/edit', [OffreController::class, 'edit'])->name('offres.edit');
        Route::put('/{id}', [OffreController::class, 'update'])->name('offres.update');
        Route::delete('/{id}', [OffreController::class, 'destroy'])->name('offres.destroy');
        Route::post('/{id}/close', [OffreController::class, 'close'])->name('offres.close');
    });

    // ========== GESTION DES CANDIDATURES (RH/Admin) ==========
    Route::middleware(['role:Admin,RH'])->prefix('admin/candidatures')->group(function () {
        Route::get('/', [CandidatureController::class, 'adminIndex'])->name('candidatures.admin');
        Route::get('/{id}', [CandidatureController::class, 'show'])->name('candidatures.show');
        Route::put('/{id}/statut', [CandidatureController::class, 'updateStatut'])->name('candidatures.statut');
        Route::post('/{id}/transform', [CandidatureController::class, 'transformToEmploye'])->name('candidature.transform');
        Route::get('/classement/{offreId}', [CandidatureController::class, 'classement'])->name('candidatures.classement');
        Route::post('/{id}/evaluations', [EvaluationController::class, 'store'])->name('evaluations.store');
    });

    // ========== PDF ==========
    Route::prefix('pdf')->group(function () {
        Route::get('/bon-conge/{id}', [PDFController::class, 'bonConge'])->name('pdf.bon_conge');
        Route::get('/apercu/{id}', [PDFController::class, 'apercuBonConge'])->name('pdf.apercu');
        Route::get('/attestation/{id}', [PDFController::class, 'attestation'])->name('pdf.attestation');
        Route::get('/apercu-attestation/{id}', [PDFController::class, 'apercuAttestation'])->name('pdf.apercu_attestation');
        Route::get('/fiche-prise-fonction/{id}', [PDFController::class, 'fichePriseFonction'])->name('pdf.fiche_prise_fonction');
Route::get('/apercu-fiche-prise-fonction/{id}', [PDFController::class, 'apercuFichePriseFonction'])->name('pdf.apercu_fiche_prise_fonction');
    });

    // ========== NOTIFICATIONS ==========
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
});