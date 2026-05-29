<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ModerationController extends Controller
{
    public function index(): Response
    {
        $places = Place::where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('ModerationPlaces', [
            'places' => $places,
        ]);
    }

    public function approve($id): RedirectResponse
    {
        $place = Place::findOrFail($id);

        $place->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Place approved successfully.',
        ]);
    }

    public function reject($id): RedirectResponse
    {
        $place = Place::findOrFail($id);

        $place->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Place rejected successfully.',
        ]);
    }
}
