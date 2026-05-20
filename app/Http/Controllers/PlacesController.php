<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PlacesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('manage-places/Manage');
    }

    public function create(): Response
    {
        return Inertia::render('manage-places/Add');
    }

    public function store()
    {
        // Backend implementation later
    }

    public function edit($id): Response
    {
        return Inertia::render('manage-places/Add', [
            'placeId' => $id,
        ]);
    }

    public function update($id)
    {
        // Backend implementation later
    }

    public function destroy($id)
    {
        // Backend implementation later
    }
}
