<?php

namespace App\Http\Requests;

use App\Enums\CustomerIdentification;
use App\Models\Identification;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
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
                function ($attribute, $value, $fail) {
                    if (request()->user()->identifications->count() > 4)
                        $fail(trans('validation.custom.max.identification'));
                },
            ],
            'type' => [
                'required',
                'integer',
                Rule::in(CustomerIdentification::keys()),
            ],
            'number' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $strlen = strlen($value);

                    switch ((int)request('type')) {
                        case CustomerIdentification::IDENTITY_CARD:
                            if (!in_array($strlen, [9, 12]))
                                $fail(trans('validation.custom.size.string_2', [
                                    'size1' => 9,
                                    'size2' => 12,
                                ]));
                            break;
                        case CustomerIdentification::CITIZEN_IDENTIFICATION_CARD:
                            if ($strlen !== 12)
                                $fail(trans('validation.size.string', [
                                    'size' => 12,
                                ]));
                            break;
                    }
                },
                'max:100',
                Rule::unique(Identification::class)->ignore(request('identification')),
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
                'after:' . Carbon::now(auth()->user()->timezone),
            ],
        ];
    }
}
