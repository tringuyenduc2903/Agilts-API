<?php

namespace App\Observers;

use App\Models\Identification;

class IdentificationObserver
{
    /**
     * Handle the Identification "created" event.
     */
    public function created(Identification $identification): void
    {
        $this->checkDefault($identification);
    }

    /**
     * @param Identification $identification
     * @return void
     */
    protected function checkDefault(Identification $identification): void
    {
        if ($identification->default) {
            auth()->user()->identifications()
                ->whereDefault(true)
                ->whereNot('id', $identification->id)
                ->update([
                    'default' => false
                ]);
        } else if (is_null(
            auth()->user()->identifications()
                ->whereDefault(true)
                ->first()
        )) {
            $identification->default = true;
            $identification->save();
        }
    }

    /**
     * Handle the Identification "updated" event.
     */
    public function updated(Identification $identification): void
    {
        $this->checkDefault($identification);
    }
}
