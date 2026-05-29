<?php

use App\Http\Controllers\ModerationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::patch('moderation/places/{id}/approve', [ModerationController::class, 'approve'])->name('moderation.places.approve');
    Route::patch('moderation/places/{id}/reject', [ModerationController::class, 'reject'])->name('moderation.places.reject');
});

