<?php

namespace App\Http\Requests;

use App\Enums\CustomerAddress;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
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
                Rule::when(
                    is_null(auth()->user()->addresses()->whereDefault(true)->first()),
                    'accepted'
                ),
                Rule::unique(Address::class)->where(function (Builder $query) {
                    /** @var Address $query */
                    return $query
                        ->whereAddressableId(auth()->user()->id)
                        ->whereAddressableType(Customer::class);
                }),
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
