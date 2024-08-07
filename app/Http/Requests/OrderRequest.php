<?php

namespace App\Http\Requests;

use App\Enums\LicensePlateRegistrationOption;
use App\Enums\OptionStatus;
use App\Enums\ProductType;
use App\Enums\RegistrationOption;
use App\Enums\ShippingType;
use App\Enums\TransactionType;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Identification;
use App\Models\Option;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'address' => [
                'required',
                'integer',
                Rule::exists(Address::class, 'id')
                    ->where('addressable_type', Customer::class)
                    ->where('addressable_id', request()->user()->id),
                function ($attribute, $value, $fail) {
                    if (!request()->user()->phone_number)
                        $fail(trans('validation.custom.required.phone_number'));
                },
            ],
            'identification' => [
                'nullable',
                'required_if:vehicle_registration_support,true',
                'integer',
                Rule::exists(Identification::class, 'id')
                    ->where('customer_id', request()->user()->id),
            ],
            'note' => [
                'nullable',
                'string',
                'max:255',
            ],
            'invoice_products' => [
                'required',
                'array',
                Rule::when(function ($attributes): bool {
                    $result = false;

                    if (is_array($attributes['invoice_products'])) {
                        $options = array_column($attributes['invoice_products'], 'option');

                        Option::whereIn('id', $options)
                            ->each(function (Option $option) use (&$result) {
                                if ($option->product->getRawOriginal('type') == ProductType::MOTOR_CYCLE)
                                    $result = true;
                            });
                    }

                    return $result;
                }, 'max:1'),
            ],
            'invoice_products.*.option' => [
                'required',
                'integer',
                Rule::exists(Option::class, 'id'),
                function ($attribute, $value, $fail) {
                    if (!$value)
                        return;

                    $option = Option::findOrFail($value);

                    if (!$option->product->enabled)
                        $fail(trans('validation.custom.product.enabled'));

                    if ($option->status === OptionStatus::OUT_OF_STOCK)
                        $fail(trans('validation.custom.product.out_of_stock'));
                },
            ],
            'invoice_products.*.amount' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
                function ($attribute, $value, $fail) {
                    if ($value < 1)
                        return;

                    $option = request(
                        str_replace('.amount', '.option', $attribute)
                    );

                    if ($option) {
                        $option_db = Option::find($option);

                        if (!$option_db)
                            return;

                        if ($value > $option_db->quantity)
                            $fail(trans('validation.max.numeric', [
                                'max' => $option_db->quantity,
                            ]));

                        if (
                            $option_db->product->getRawOriginal('type') == ProductType::MOTOR_CYCLE &&
                            $value > 1
                        )
                            $fail(trans('validation.max.numeric', [
                                'max' => 1,
                            ]));
                    }
                },
            ],
            'vehicle_registration_support' => [
                'required',
                'boolean',
            ],
            'registration_option' => [
                'required_if:vehicle_registration_support,true',
                'integer',
                Rule::in(RegistrationOption::keys()),
            ],
            'license_plate_registration_option' => [
                'required_if:vehicle_registration_support,true',
                'integer',
                Rule::in(LicensePlateRegistrationOption::keys()),
            ],
            'shipping_type' => [
                'required',
                'integer',
                Rule::in(ShippingType::keys()),
            ],
            'transaction_type' => [
                'required',
                'integer',
                Rule::in(TransactionType::keys()),
            ],
        ];
    }
}
