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

Route::get('/admin/pdf-preview/{sourcePackage}', function (\App\Models\SourcePackage $sourcePackage) {
    if (!auth()->check() || !auth()->user()->isAdmin()) {
        abort(403);
    }
    $path = $sourcePackage->paper_path;
    if (!$path || !\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
        abort(404, 'PDF file not found');
    }
    return response()->file(\Illuminate\Support\Facades\Storage::disk('local')->path($path), [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="paper.pdf"',
    ]);
})->middleware(['signed'])->name('admin.pdf.preview');

// Main routes (load after assessment routes for fallback)
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
