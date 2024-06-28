<?php

namespace App\Http\Controllers;

use App\Enums\Provider;
use App\Models\Customer;
use App\Models\Social;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected User $user;

    protected int $provider_name;

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
        $this->user = Socialite::driver($driver_name)->user();

        $this->provider_name = Provider::keyForValue($driver_name);

        $customer = Customer::whereEmail($this->user->getEmail())
            ->orWhereHas(
                'socials',
                function (Builder $query): Builder {
                    /** @var Social $query */
                    return $query
                        ->whereProviderId($this->user->getId())
                        ->whereProviderName($this->provider_name);
                })
            ->first();

        $customer
            ? $this->login($customer)
            : $this->register();

        return redirect(config('app.frontend_url'));
    }

    /**
     * @param Customer $customer
     * @return void
     */
    protected function login(Customer $customer)
    {
        Social::createOrFirst([
            'provider_id' => $this->user->getId(),
            'provider_name' => $this->provider_name,
        ], [
            'customer_id' => $customer->id,
        ]);

        if (
            $this->user->getEmail() === $customer->email &&
            !$customer->hasVerifiedEmail()
        )
            $customer->markEmailAsVerified();

        auth()->login($customer, true);

        session()->regenerate();
    }

    /**
     * @return void
     */
    protected function register()
    {
        $customer = Customer::create([
            'name' => $this->user->getName(),
            'email' => $this->user->getEmail(),
            'password' => Str::password(20),
            'timezone' => config('app.timezone'),
        ]);

        Social::updateOrCreate([
            'provider_id' => $this->user->getId(),
            'provider_name' => $this->provider_name,
        ], [
            'customer_id' => $customer->id,
        ]);

        $customer->markEmailAsVerified();

        auth()->login($customer, true);

        session()->regenerate();
    }
}
