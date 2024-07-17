<?php

namespace App\Observers;

use App\Models\Address;

class AddressObserver
{
    /**
     * Handle the Address "updated" event.
     */
    public function updated(Address $address): void
    {
        $this->created($address);
    }

    /**
     * Handle the Address "created" event.
     */
    public function created(Address $address): void
    {
        $addresses = auth()->user()->addresses();

        if ($address->default)
            $address->whereDefault(true)
                ->whereNot('id', $address->id)
                ->update(['default' => false]);
        else
            $addresses->whereDefault(true)->firstOr(
                fn() => $address
                    ->fill(['default' => true])
                    ->save()
            );
    }
}
