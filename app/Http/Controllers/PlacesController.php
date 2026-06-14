<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreePlaceRequest;
use App\Models\Category;
use App\Models\Place;
use App\Services\ImageUploadService;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PlacesController extends Controller
{
    public function index(): Response
    {
        $places = Place::where('user_id', auth()->id())->with('category', 'images')->get();

        return Inertia::render('manage-places/Manage', [
            'places' => $places,
        ]);
    }

    public function create(): Response
    {
        $categories = Category::all();

        return Inertia::render('manage-places/Add', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreePlaceRequest $request, ImageUploadService $imageUploadService)
    {
        $validated = $request->validated();

        $category = Category::where('slug', $validated['category'])->first();

        $place = Place::create([
            'user_id' => $request->user()->id,
            'category_id' => $category->id,
            'name' => $validated['placeName'],
            'slug' => Str::slug($validated['placeName']).'-'.Str::random(6),
            'description' => $validated['description'],
            'address' => $validated['adress'],
            'city' => $validated['city'],
            'province' => $validated['province'] ?? '',
            'country' => $validated['country'] ?? '',
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'status' => 'pending',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageData = $imageUploadService->upload($image, $place->id);
                $place->images()->create($imageData);
            }
        }

        $place->load('images');

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Place added successfully. Awaiting admin approval.')]);

        return back();
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
        $place = Place::findOrFail($id);

        if ($place->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $place->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Place deleted successfully.')]);

        return back();
    }
}
