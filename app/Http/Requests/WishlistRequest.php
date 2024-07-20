<?php

namespace App\Http\Requests;

use App\Models\Option;
use App\Models\ProductList;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WishlistRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'version' => [
                'required',
                'integer',
                Rule::exists(Option::class, 'id'),
                Rule::unique(ProductList::class, 'option_id')
                    ->where('type', \App\Enums\ProductList::WISHLIST)
                    ->where('customer_id', request()->user()->id),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'version.unique' => trans('The product is already in :list', [
                'list' => trans('Wishlist'),
            ]),
        ];
    }
}
