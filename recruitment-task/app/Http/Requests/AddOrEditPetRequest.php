<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddOrEditPetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'integer|min:0',
            'name' => 'required|string|max:255',
            'category' => 'nullable|in:' . implode(',', array_keys(config('categories'))),
            'image' => ['array'],
            'image.*' => ['image', 'mimes:jpg,jpeg,png'],
            'tags' => 'nullable|array',
            'status' => 'nullable|string|in:available,pending,sold',
        ];
    }
}
