<?php

namespace App\Observers;

use App\Models\Address;

class AddressObserver
{
    /**
     * Handle the Address "updating" event.
     */
    public function updating(Address $address): void
    {
        auth()->user()->addresses()
            ->whereDefault(true)
            ->firstOr(
                fn() => $address->default = true
            );
    }

    /**
     * Handle the Address "creating" event.
     */
    public function creating(Address $address): void
    {
        $addresses = auth()->user()->addresses();

        $address->default
            ? $address->whereDefault(true)->update([
            'default' => false,
        ])
            : $addresses->whereDefault(true)->firstOr(
            fn() => $address->default = true
        );
    }
}
