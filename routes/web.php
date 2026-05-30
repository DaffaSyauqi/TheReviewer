<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\ModerationController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::inertia('/review', 'Review')->name('review');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('manage-places', 'ManagePlaces')->name('manage-places');
    Route::get('moderation-places', [ModerationController::class, 'index'])->name('moderation-places');
});

require __DIR__.'/settings.php';
require __DIR__.'/manage-places.php';
require __DIR__.'/moderation.php';
