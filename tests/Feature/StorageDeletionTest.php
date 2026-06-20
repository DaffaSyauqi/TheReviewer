<?php

use App\Models\Category;
use App\Models\Place;
use App\Models\PlaceImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('s3');
});

test('deleting a place image removes file from s3 storage', function () {
    $category = Category::factory()->create();
    $place = Place::factory()->create(['category_id' => $category->id]);
    $path = "{$place->id}/1718931234-test.webp";

    Storage::disk('s3')->put($path, 'fake-image-data');
    expect(Storage::disk('s3')->exists($path))->toBeTrue();

    $image = PlaceImage::create([
        'place_id' => $place->id,
        'disk' => 's3',
        'path' => $path,
        'mime_type' => 'image/webp',
        'size' => 1000,
    ]);

    $image->delete();

    expect(Storage::disk('s3')->exists($path))->toBeFalse();
    expect(PlaceImage::find($image->id))->toBeNull();
});

test('deleting a place removes all associated image files from s3 storage', function () {
    $category = Category::factory()->create();
    $place = Place::factory()->create(['category_id' => $category->id]);

    $path1 = "{$place->id}/1718931234-aaa.webp";
    $path2 = "{$place->id}/1718931235-bbb.webp";

    Storage::disk('s3')->put($path1, 'fake-image-data-1');
    Storage::disk('s3')->put($path2, 'fake-image-data-2');

    PlaceImage::create([
        'place_id' => $place->id,
        'disk' => 's3',
        'path' => $path1,
        'mime_type' => 'image/webp',
        'size' => 1000,
    ]);

    PlaceImage::create([
        'place_id' => $place->id,
        'disk' => 's3',
        'path' => $path2,
        'mime_type' => 'image/webp',
        'size' => 2000,
    ]);

    expect(Storage::disk('s3')->exists($path1))->toBeTrue();
    expect(Storage::disk('s3')->exists($path2))->toBeTrue();

    $place->delete();

    expect(Storage::disk('s3')->exists($path1))->toBeFalse();
    expect(Storage::disk('s3')->exists($path2))->toBeFalse();
    expect(Place::find($place->id))->toBeNull();
    expect(PlaceImage::where('place_id', $place->id)->count())->toBe(0);
});

test('deleting a user removes all place image files from s3 storage', function () {
    $category = Category::factory()->create();
    $user = User::factory()->create();

    $place1 = Place::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
    $place2 = Place::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);

    $path1 = "{$place1->id}/1718931234-img1.webp";
    $path2 = "{$place2->id}/1718931235-img2.webp";

    Storage::disk('s3')->put($path1, 'fake-image-data-1');
    Storage::disk('s3')->put($path2, 'fake-image-data-2');

    PlaceImage::create([
        'place_id' => $place1->id,
        'disk' => 's3',
        'path' => $path1,
        'mime_type' => 'image/webp',
        'size' => 1000,
    ]);

    PlaceImage::create([
        'place_id' => $place2->id,
        'disk' => 's3',
        'path' => $path2,
        'mime_type' => 'image/webp',
        'size' => 2000,
    ]);

    expect(Storage::disk('s3')->exists($path1))->toBeTrue();
    expect(Storage::disk('s3')->exists($path2))->toBeTrue();

    $user->delete();

    expect(Storage::disk('s3')->exists($path1))->toBeFalse();
    expect(Storage::disk('s3')->exists($path2))->toBeFalse();
    expect(User::find($user->id))->toBeNull();
    expect(Place::where('user_id', $user->id)->count())->toBe(0);
    expect(PlaceImage::where('place_id', $place1->id)->count())->toBe(0);
    expect(PlaceImage::where('place_id', $place2->id)->count())->toBe(0);
});

test('deleting a place with no images does not error', function () {
    $category = Category::factory()->create();
    $place = Place::factory()->create(['category_id' => $category->id]);

    $place->delete();

    expect(Place::find($place->id))->toBeNull();
});

test('deleting a user with no places does not error', function () {
    $user = User::factory()->create();

    $user->delete();

    expect(User::find($user->id))->toBeNull();
});

test('deleting a place image with null disk or path does not error', function () {
    $category = Category::factory()->create();
    $place = Place::factory()->create(['category_id' => $category->id]);

    $image = PlaceImage::create([
        'place_id' => $place->id,
        'disk' => 's3',
        'path' => 'nonexistent/path.webp',
        'mime_type' => 'image/webp',
        'size' => 1000,
    ]);

    Storage::disk('s3')->shouldNotExist('nonexistent/path.webp');

    $image->delete();

    expect(PlaceImage::find($image->id))->toBeNull();
});
