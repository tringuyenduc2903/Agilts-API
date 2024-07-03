<?php

namespace App\Actions\Fortify;

use App\Enums\Gender;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateCustomerProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param Customer $user
     * @param array<string, string> $input
     */
    public function update(Customer $user, array $input): void
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
                Rule::unique(Customer::class)->ignore($user->id),
            ],
            'phone_number' => [
                'nullable',
                'string',
                'phone:VN',
                Rule::unique(Customer::class)->ignore($user->id),
            ],
            'birthday' => [
                'nullable',
                'date',
                'before_or_equal:' . Carbon::now()->subYears(16),
                'after_or_equal:' . Carbon::now()->subYears(100),
            ],
            'gender' => [
                'nullable',
                'integer',
                Rule::in(Gender::keys()),
            ],
            'timezone' => [
                'required',
                'string',
                'max:30',
                Rule::in(timezone_identifiers_list()),
            ],
        ])->validate();

        $user->fill($validate);

        $this->updateVerifiedUser($user);

        $user->save();
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param Customer $user
     */
    protected function updateVerifiedUser(Customer $user): void
    {
        if ($user->isDirty('email') && $user instanceof MustVerifyEmail) {
            $user
                ->forceFill(['email_verified_at' => null])
                ->save();

            $user->sendEmailVerificationNotification();
        }

        if ($user->isDirty('phone_number')) {
            $user
                ->forceFill(['phone_number_verified_at' => null])
                ->save();
        }
    }
}
