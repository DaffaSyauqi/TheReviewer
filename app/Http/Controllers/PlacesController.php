<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
        $categories = Category::all();

        return Inertia::render('manage-places/Add', [
            'categories' => $categories,
        ]);
    }

    public function store()
    {
        // Backend implementation later
    }

    public function edit($id): Response
    {
        $categories = Category::all();

        return Inertia::render('manage-places/Add', [
            'placeId' => $id,
            'categories' => $categories,
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
