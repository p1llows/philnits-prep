<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Import assessment routes
require __DIR__.'/assessment.php';

// Import topic and review routes
require __DIR__.'/review.php';

// Import analytics routes
require __DIR__.'/analytics.php';

// Import admin routes (load last for priority)
require __DIR__.'/admin.php';

// Import study planning routes
require __DIR__.'/study.php';

// Main routes (load after assessment routes for fallback)
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Check if user is admin to show admin dashboard widget
    $user = auth()->user();
    if ($user && $user->role === 'admin') {
        return view('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
