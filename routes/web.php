<?php

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemplateDocumentController;
use App\Http\Controllers\ProcessedDocumentController;
use App\Http\Controllers\Api\TemplateApiController;
use App\Http\Controllers\Api\ProcessedApiController;

// Web Routes
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
