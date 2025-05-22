<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InboundEmailController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\AiController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/ai/select-resource', [AiController::class, 'selectResource'])->name('ai.selectResource');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/inbound-email', [InboundEmailController::class, 'handle'])->name('inbound.email.handle');

require __DIR__.'/auth.php';
