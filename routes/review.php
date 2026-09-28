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

// Browse all topics
Route::get('/topics', [TopicController::class, 'index'])
    ->middleware(['auth'])
    ->name('topics.index');

// View specific topic with questions
Route::get('/topics/{topic}', [TopicController::class, 'show'])
    ->middleware(['auth'])
    ->name('topics.show');

// Practice a specific topic
Route::get('/topics/{topic}/practice', [TopicController::class, 'practice'])
    ->middleware(['auth'])
    ->name('topics.practice');

// Start practice session (general)
Route::get('/practice', function () {
    return view('practice.index');
})->middleware(['auth'])->name('practice.start');

// Mistake review
Route::get('/mistakes', function () {
    return view('mistakes.index');
})->middleware(['auth'])->name('mistakes.index');
