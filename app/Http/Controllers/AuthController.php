<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Social;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * @param string $driver_name
     * @return RedirectResponse
     */
    public function redirect(string $driver_name): RedirectResponse
    {
        return Socialite::driver($driver_name)->redirect();
    }

    /**
     * @param string $driver_name
     * @return RedirectResponse
     */
    public function callback(string $driver_name): RedirectResponse
    {
        $user = Socialite::driver($driver_name)->user();

        $social = Social::whereProviderId($user->getId())
            ->whereProviderName($driver_name)
            ->first();

        $customer = $social
            ? $social->customer
            : Customer::whereEmail($user->getEmail())->first();

        if (!auth()->check()) {
            auth()->logout();

            session()->regenerate();
        }

        if ($customer)
            $this->login($customer, $user, $driver_name);
        else
            $this->register($user, $driver_name);

        return redirect(config('app.frontend_url'));
    }

    /**
     * @param Customer $customer
     * @param User $user
     * @param string $driver_name
     * @return void
     */
    protected function login(Customer $customer, User $user, string $driver_name)
    {
        Social::createOrFirst([
            'provider_id' => $user->getId(),
            'provider_name' => $driver_name,
        ], [
            'customer_id' => $customer->id,
        ]);

        if (
            $user->getEmail() === $customer->email &&
            !$customer->hasVerifiedEmail()
        )
            $customer->markEmailAsVerified();

        auth()->login($customer, true);

        session()->regenerate();
    }

    /**
     * @param User $user
     * @param string $driver_name
     * @return void
     */
    protected function register(User $user, string $driver_name)
    {
        $customer = Customer::create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => Str::password(20),
        ]);

        Social::updateOrCreate([
            'provider_id' => $user->getId(),
            'provider_name' => $driver_name,
        ], [
            'customer_id' => $customer->id,
        ]);

        $customer->markEmailAsVerified();

        auth()->login($customer, true);

        session()->regenerate();
    }
}
