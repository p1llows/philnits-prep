<?php

use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Topic & Review Routes
|--------------------------------------------------------------------------
|
| Routes for browsing topics, practice mode, and mistake reviews.
| These provide learning and improvement features for learners.
|
*/

Route::middleware(['auth', 'pretest.completed'])->group(function () {
    // Browse all topics
    Route::get('/topics', [TopicController::class, 'index'])
        ->name('topics.index');

    // View specific topic with questions
    Route::get('/topics/{topic}', [TopicController::class, 'show'])
        ->name('topics.show');

    // Practice a specific topic
    Route::get('/topics/{topic}/practice', [TopicController::class, 'practice'])
        ->name('topics.practice');

    // Start practice session (general)
    Route::get('/practice', function () {
        return view('practice.index');
    })->name('practice.start');

    // Mistake review
    Route::get('/mistakes', function () {
        return view('mistakes.index');
    })->name('mistakes.index');
});
