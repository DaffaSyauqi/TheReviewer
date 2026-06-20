<?php

namespace Database\Factories;

use App\Models\Place;
use App\Models\PlaceImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<PlaceImage>
 */
class PlaceImageFactory extends Factory
{
    protected static ?string $disk = 's3';

    public function definition(): array
    {
        $place = Place::factory()->create();
        $path = "{$place->id}/{$this->faker->randomNumber()}-test.webp";

        Storage::disk(self::$disk)->put($path, 'fake-image-data');

        return [
            'place_id' => $place->id,
            'disk' => self::$disk,
            'path' => $path,
            'mime_type' => 'image/webp',
            'size' => 1000,
        ];
    }
}
