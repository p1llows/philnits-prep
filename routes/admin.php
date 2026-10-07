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
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Topics CRUD
    Route::resource('topics', TopicController::class);
    Route::post('/topics/{topic}/toggle-status', [TopicController::class, 'toggleStatus'])
        ->name('topics.toggle-status');

    // Questions CRUD
    Route::resource('questions', \App\Http\Controllers\Admin\QuestionController::class);
    Route::post('/questions/{question}/publish', [\App\Http\Controllers\Admin\QuestionController::class, 'publish'])
        ->name('questions.publish');

    // Source Packages CRUD & Import
    Route::resource('source-packages', \App\Http\Controllers\Admin\SourcePackageController::class);
    Route::post('/source-packages/{sourcePackage}/import-questions', [\App\Http\Controllers\Admin\SourcePackageController::class, 'importQuestions'])
        ->name('source-packages.import-questions');
});

