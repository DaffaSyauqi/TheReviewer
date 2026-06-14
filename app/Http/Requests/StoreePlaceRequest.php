<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreePlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'placeName' => ['required', 'string', 'max:255'],
            'category' => ['required', 'exists:categories,slug'],
            'description' => ['required', 'string', 'max:1000'],
            'adress' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => ['image', 'mimes:jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'placeName.required' => 'Place name is required.',
            'category.required' => 'Category is required.',
            'category.exists' => 'Selected category is invalid.',
            'description.required' => 'Description is required.',
            'adress.required' => 'Address is required.',
            'city.required' => 'City is required.',
            'latitude.required' => 'Location is required. Please select a location on the map.',
            'longitude.required' => 'Location is required. Please select a location on the map.',
            'images.max' => 'You can upload a maximum of 3 images.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.mimes' => 'Only JPEG, PNG, and WebP images are allowed.',
            'images.*.max' => 'Each image must not exceed 5MB.',
        ];
    }
}
