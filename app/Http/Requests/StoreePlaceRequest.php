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
        ];
    }
}
