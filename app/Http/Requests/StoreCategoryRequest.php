<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You can adjust this to implement user authorization if needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'categoryName' => 'required|string|max:255',
            'categoryDescription' => 'nullable|string',
        ];
    }

    /**
     * Customize the validation error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'categoryName.required' => 'The category name is required.',
            'categoryName.string' => 'The category name must be a string.',
            'categoryName.max' => 'The category name may not be greater than 255 characters.',
            'categoryDescription.string' => 'The category description must be a string.',
        ];
    }
}
