<?php

namespace App\Http\Requests;

use App\Models\Identification;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IdentificationRequest extends FormRequest
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
                Rule::in(\App\Enums\Identification::keys()),
            ],
            'number' => [
                'required',
                'string',
                'min:9',
                'max:100',
                Rule::unique(Identification::class)->ignore($this->input('id')),
            ],
            'issued_name' => [
                'required',
                'string',
                'max:255',
            ],
            'issuance_date' => [
                'required',
                'date',
            ],
            'expiry_date' => [
                'required',
                'date',
                'after:issuance_date',
            ],
        ];
    }
}
