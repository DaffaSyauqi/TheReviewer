<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Place;
use Inertia\Inertia;
use Inertia\Response;

class ReviewController extends Controller
{
    public function index(): Response
    {
        $places = Place::where('status', 'approved')
            ->with('category', 'images')
            ->get();

        $allCount = $places->count();

        $categories = Category::withCount(['places' => fn ($query) => $query->where('status', 'approved')])
            ->get()
            ->map(fn (Category $category): array => [
                'id' => $category->slug,
                'label' => $category->name,
                'icon' => $category->icon,
                'count' => $category->places_count,
            ]);

        $categories = $categories->prepend([
            'id' => 'all',
            'label' => 'Semua',
            'icon' => 'LayoutGrid',
            'count' => $allCount,
        ]);

        return Inertia::render('Review', [
            'places' => $places,
            'categories' => $categories,
        ]);
    }
}
