<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Course title is required',
            'title.max' => 'Course title cannot exceed 255 characters',
            'description.required' => 'Course description is required',
            'instructor.required' => 'Instructor name is required',
            'category.required' => 'Category is required',
            'duration.required' => 'Duration is required',
            'duration.min' => 'Duration must be at least 1 minute',
            'price.required' => 'Price is required',
            'price.min' => 'Price cannot be negative',
        ];
    }
}
