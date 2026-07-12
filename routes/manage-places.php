<?php

use App\Http\Controllers\PlacesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('manage-places', function () {
        return redirect()->route('places.index');
    });

    Route::get('manage-places/index', [PlacesController::class, 'index'])->name('places.index');
    Route::get('manage-places/create', [PlacesController::class, 'create'])->name('places.create');
    Route::post('manage-places', [PlacesController::class, 'store'])->name('places.store');
    Route::get('manage-places/{id}/edit', [PlacesController::class, 'edit'])->name('places.edit');
    Route::patch('manage-places/{id}', [PlacesController::class, 'update'])->name('places.update');
    Route::delete('manage-places/{id}', [PlacesController::class, 'destroy'])->name('places.destroy');
});
