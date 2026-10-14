<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\StudyPlanner;
use App\Livewire\StudyGoals;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Study Planning Routes
|--------------------------------------------------------------------------
|
| Routes for study scheduling, goal setting, and time management tools.
| These help learners organize their preparation effectively.
|
*/

Route::middleware(['auth', 'pretest.completed'])->group(function () {
    // Main planner page
    Route::get('/planner', function () {
        return view('study.planner');
    })->name('planner.index');

    // Planner Livewire components (embedded in views)
    Route::get('/planner/schedules', StudyPlanner::class)
        ->name('planner.schedules');

    Route::get('/planner/goals', StudyGoals::class)
        ->name('planner.goals');
});
