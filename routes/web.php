<?php

use App\Http\Controllers\ShortLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShortLinkController::class, 'index'])->name('short-links.index');
Route::post('/short-links', [ShortLinkController::class, 'store'])->name('short-links.store');
Route::patch('/short-links/{id}/deactivate', [ShortLinkController::class, 'deactivate'])->name('short-links.deactivate');
Route::post('/short-links/{id}/deactivate', [ShortLinkController::class, 'deactivate']);
Route::delete('/short-links/{id}', [ShortLinkController::class, 'destroy'])->name('short-links.destroy');
Route::get('/{code}', [ShortLinkController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9-]+')
    ->name('short-links.redirect');
