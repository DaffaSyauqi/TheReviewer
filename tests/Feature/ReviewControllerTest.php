<?php

use App\Models\Category;
use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('guests can access the review page', function () {
    $response = $this->get(route('review'));

    $response->assertOk();
});

test('review page returns approved places only', function () {
    $category = Category::factory()->create(['name' => 'Wisata', 'slug' => 'wisata']);

    Place::factory()->create([
        'category_id' => $category->id,
        'status' => 'approved',
        'name' => 'Approved Place',
    ]);

    Place::factory()->create([
        'category_id' => $category->id,
        'status' => 'pending',
        'name' => 'Pending Place',
    ]);

    Place::factory()->create([
        'category_id' => $category->id,
        'status' => 'rejected',
        'name' => 'Rejected Place',
    ]);

    $this->get(route('review'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Review')
            ->where('places', function ($places) {
                return count($places) === 1 && $places[0]['name'] === 'Approved Place';
            })
        );
});

test('review page returns categories with approved place counts', function () {
    $wisata = Category::factory()->create(['name' => 'Wisata', 'slug' => 'wisata', 'icon' => 'Camera']);
    $kuliner = Category::factory()->create(['name' => 'Kuliner', 'slug' => 'kuliner', 'icon' => 'UtensilsCrossed']);

    Place::factory()->count(2)->create(['category_id' => $wisata->id, 'status' => 'approved']);
    Place::factory()->count(3)->create(['category_id' => $kuliner->id, 'status' => 'approved']);
    Place::factory()->create(['category_id' => $wisata->id, 'status' => 'pending']);

    $this->get(route('review'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Review')
            ->where('categories', function ($categories) {
                $all = collect($categories)->firstWhere('id', 'all');
                $wisata = collect($categories)->firstWhere('id', 'wisata');
                $kuliner = collect($categories)->firstWhere('id', 'kuliner');

                return $all['count'] === 5 && $wisata['count'] === 2 && $kuliner['count'] === 3;
            })
        );
});

test('review page returns empty data when no approved places exist', function () {
    Category::factory()->create(['name' => 'Wisata', 'slug' => 'wisata']);
    Place::factory()->create(['status' => 'pending']);

    $this->get(route('review'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Review')
            ->where('places', [])
        );
});
