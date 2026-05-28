<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    public function geocode(string $address, string $city, ?string $country = null): array
    {
        $fullAddress = trim("$address, $city" . ($country ? ", $country" : ""));

        try {
            $response = Http::timeout(10)
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $fullAddress,
                    'format' => 'json',
                    'limit' => 1,
                ]);

            if ($response->successful() && $response->json()) {
                $result = $response->json()[0];

                return [
                    'latitude' => (float) $result['lat'],
                    'longitude' => (float) $result['lon'],
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Geocoding failed: ' . $e->getMessage());
        }

        return [
            'latitude' => 0.0,
            'longitude' => 0.0,
        ];
    }
}
