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

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Topics CRUD
    Route::resource('topics', TopicController::class);
    Route::post('/topics/{topic}/toggle-status', [TopicController::class, 'toggleStatus'])
        ->name('topics.toggle-status');
});
