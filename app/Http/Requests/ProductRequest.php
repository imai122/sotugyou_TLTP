<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'  => 'required|exists:categories,category_id',
            'product_id' => 'nullable',
            'product_name' => 'required|string|max:50',
            'image_path' => 'nullable|image|mimes:jpeg,png,gif,img|max:10000',
            'comment' => 'required|max:100',
            'wish_price' => 'required|integer',
            'end_date' => 'required|date',
            
            //
        ];
    }
}
