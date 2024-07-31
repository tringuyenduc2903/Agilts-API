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
        $this->creating($address);
    }

    /**
     * Handle the Address "creating" event.
     */
    public function creating(Address $address): void
    {
        $addresses = auth()->user()->addresses();

        if ($address->default)
            $address->whereDefault(true)
                ->update(['default' => false]);
        else
            $addresses->whereDefault(true)->firstOr(
                fn() => $address->default = true
            );
    }
}
