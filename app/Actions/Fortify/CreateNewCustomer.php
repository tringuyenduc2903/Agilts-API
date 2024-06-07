<?php

namespace App\Actions\Fortify;

use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewCustomer implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     * @return Customer
     */
    public function create(array $input): Customer
    {
        $validate = Validator::make($input, [
            'name' => [
                'required',
                'string',
                'max:50',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique(Customer::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return Customer::create($validate);
    }
}
