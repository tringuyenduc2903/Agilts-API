<?php

namespace App\Observers;

use App\Models\Identification;

class IdentificationObserver
{
    /**
     * Handle the Identification "updated" event.
     */
    public function updated(Identification $identification): void
    {
        $this->created($identification);
    }

    /**
     * Handle the Identification "created" event.
     */
    public function created(Identification $identification): void
    {
        $identifications = auth()->user()->identifications();

        if ($identification->default)
            $identification->whereDefault(true)
                ->whereNot('id', $identification->id)
                ->update(['default' => false]);
        else
            $identifications->whereDefault(true)->firstOr(
                fn() => $identification
                    ->fill(['default' => true])
                    ->save()
            );
    }
}
