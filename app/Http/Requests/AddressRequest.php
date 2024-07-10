<?php

namespace App\Http\Requests;

use App\Enums\CustomerAddress;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'default' => [
                'required',
                'boolean',
            ],
            'type' => [
                'required',
                'integer',
                Rule::in(CustomerAddress::keys()),
            ],
            'country' => [
                'required',
                'string',
                'max:100',
            ],
            'province' => [
                'required',
                'string',
                'max:100',
            ],
            'district' => [
                'required',
                'string',
                'max:100',
            ],
            'ward' => [
                'nullable',
                'string',
                'max:100',
            ],
            'address_detail' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
