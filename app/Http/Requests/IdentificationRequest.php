<?php

namespace App\Http\Requests;

use App\Models\Identification;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
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
                Rule::when(
                    is_null(auth()->user()->identifications()->whereDefault(true)->first()),
                    'accepted'
                ),
                Rule::unique(Identification::class)->where(function (Builder $query) {
                    /** @var Identification $query */
                    return $query->whereCustomerId(auth()->user()->id);
                }),
            ],
            'type' => [
                'required',
                'integer',
                Rule::in(\App\Enums\Identification::keys()),
            ],
            'number' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $strlen = strlen($value);

                    switch ((int)request()->input('type')) {
                        case \App\Enums\Identification::IDENTITY_CARD:
                            if (!in_array($strlen, [9, 12]))
                                $fail(trans('validation.custom.size.string_2', [
                                    'size1' => 9,
                                    'size2' => 12,
                                ]));
                            break;
                        case \App\Enums\Identification::CITIZEN_IDENTIFICATION_CARD:
                            if ($strlen !== 12)
                                $fail(trans('validation.size.string', [
                                    'size' => 12,
                                ]));
                            break;
                    }
                },
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
                'after:' . Carbon::now(auth()->user()->timezone),
            ],
        ];
    }
}
