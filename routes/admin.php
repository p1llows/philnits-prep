<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TopicController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Administrative routes for managing content (Topics, Questions, Source Packages).
| These routes require admin authorization through policy checks.
|
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard - redirect to Filament panel
    Route::get('/dashboard', function () {
        return redirect('/admin');
    })->name('dashboard');

    // Legacy action hooks & store endpoints kept for backward compatibility
    Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');
    Route::post('/topics/{topic}/toggle-status', [TopicController::class, 'toggleStatus'])
        ->name('topics.toggle-status');

    Route::post('/questions', [\App\Http\Controllers\Admin\QuestionController::class, 'store'])->name('questions.store');
    Route::post('/questions/{question}/publish', [\App\Http\Controllers\Admin\QuestionController::class, 'publish'])
        ->name('questions.publish');

    Route::post('/source-packages', [\App\Http\Controllers\Admin\SourcePackageController::class, 'store'])->name('source-packages.store');
    Route::post('/source-packages/{sourcePackage}/import-questions', [\App\Http\Controllers\Admin\SourcePackageController::class, 'importQuestions'])
        ->name('source-packages.import-questions');
});

