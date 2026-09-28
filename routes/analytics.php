<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ProgressAnalytics;

/*
|--------------------------------------------------------------------------
| Analytics Routes
|--------------------------------------------------------------------------
|
| Routes for user progress tracking and performance visualization.
| Provides insights into learning effectiveness and mastery progression.
|
*/

Route::get('/analytics', ProgressAnalytics::class)
    ->middleware(['auth'])
    ->name('analytics.index');
