<?php

use App\Http\Controllers\ModerationController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::get('/review', [ReviewController::class, 'index'])->name('review');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::get('moderation-places', [ModerationController::class, 'index'])->name('moderation-places');
});

require __DIR__.'/settings.php';
require __DIR__.'/manage-places.php';
require __DIR__.'/moderation.php';
