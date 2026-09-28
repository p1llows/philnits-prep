<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentController;
use App\Livewire\TakeAssessment;
use App\Livewire\Assessments\ResultsPage;

Route::middleware(['auth'])->group(function () {
    // Initial assessment route
    Route::get('/assessment', [AssessmentController::class, 'index'])
        ->name('assessment.index');
    
    // View active/partial assessment
    Route::get('/assessment/{assessment}', [AssessmentController::class, 'show'])
        ->name('assessment.show');
    
    // Submit assessment
    Route::post('/assessment/{assessment}/submit', [AssessmentController::class, 'submit'])
        ->name('assessment.submit');
    
    // View results
    Route::get('/assessment/{assessment}/results', [AssessmentController::class, 'results'])
        ->name('assessment.results');
    
    // Assessment history
    Route::get('/assessment/history', [AssessmentController::class, 'history'])
        ->name('assessment.history');
});
