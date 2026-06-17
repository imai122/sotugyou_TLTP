<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BidRequest extends FormRequest
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

        'bid_amount'   => 'required|integer|min:1',

            //
        ];
    }

    public function messages()
{
    return [
        'bid_amount.min' => '入札金額は希望価格以上の金額を入力してください。',
    ];
}
}
