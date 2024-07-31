<?php

namespace App\Observers;

use App\Models\Identification;

class IdentificationObserver
{
    /**
     * Handle the Identification "updating" event.
     */
    public function updating(Identification $identification): void
    {
        $this->creating($identification);
    }

    /**
     * Handle the Identification "creating" event.
     */
    public function creating(Identification $identification): void
    {
        $identifications = auth()->user()->identifications();

        if ($identification->default)
            $identification->whereDefault(true)
                ->update(['default' => false]);
        else
            $identifications->whereDefault(true)->firstOr(
                fn() => $identification->default = true
            );
    }
}
