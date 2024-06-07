<?php

namespace App\Actions\Fortify;

use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateCustomerPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param Customer $user
     * @param array<string, string> $input
     */
    public function update(Customer $user, array $input): void
    {
        Validator::make($input, [
            'current_password' => [
                'required',
                'string',
                'current_password:web',
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user
            ->forceFill(['password' => $input['password']])
            ->save();
    }
}
