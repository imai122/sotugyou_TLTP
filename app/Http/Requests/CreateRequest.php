<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            
            'user_id' => 'required|unique:yic_users,user_id',
            'password' => 'required|max:100',
            'name' => 'required|max:100',
            'postal_code' => 'required',
            'phone_number' => 'required',
            'email' => 'required|max:255',
            'bank_account' => 'required|max:255',
            'address' => 'required|max:255',
            'role' => ['required', 'integer', 'in:3,4'],
            //
        ];
    }
}
