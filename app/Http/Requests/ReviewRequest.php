<?php

namespace App\Http\Requests;

use App\Models\ProductOption;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'content' => [
                'required',
                'string',
                'max:255',
            ],
            'rate' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'images' => [
                'nullable',
                'array',
                'max:5',
            ],
            'images.*' => [
                'required',
                'string',
                'max:255',
            ],
            'version' => [
                'required',
                'integer',
                Rule::exists(ProductOption::class, 'id'),
            ],
        ];
    }
}
