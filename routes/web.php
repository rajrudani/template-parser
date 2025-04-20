<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemplateDocumentController;
use App\Http\Controllers\ProcessedDocumentController;
use App\Http\Controllers\Api\TemplateApiController;
use App\Http\Controllers\Api\ProcessedApiController;

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


// Route::middleware(['auth'])->group(function () {
    // Template routes (Super Admin access)
    Route::resource('templates', TemplateDocumentController::class)
        ->except(['edit', 'update']);
    
    Route::post('templates/{template}/preview', [TemplateDocumentController::class, 'preview'])
        ->name('templates.preview');
    
    // Processed document routes (Principal access)
    Route::resource('processed', ProcessedDocumentController::class)
        ->only(['index', 'edit', 'update']);
    
    Route::get('processed/{document}/export', [ProcessedDocumentController::class, 'export'])
        ->name('processed.export');
// });

require __DIR__.'/auth.php';