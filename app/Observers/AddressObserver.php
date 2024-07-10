<?php

namespace App\Observers;

use App\Models\Address;

class AddressObserver
{
    /**
     * Handle the Address "created" event.
     */
    public function created(Address $address): void
    {
        $this->checkDefault($address);
    }

    /**
     * @param Address $address
     * @return void
     */
    protected function checkDefault(Address $address): void
    {
        if ($address->default) {
            auth()->user()->addresses()
                ->whereDefault(true)
                ->whereNot('id', $address->id)
                ->update([
                    'default' => false
                ]);
        } else if (is_null(
            auth()->user()->addresses()
                ->whereDefault(true)
                ->first()
        )) {
            $address->default = true;
            $address->save();
        }
    }

    /**
     * Handle the Address "updated" event.
     */
    public function updated(Address $address): void
    {
        $this->checkDefault($address);
    }
}
