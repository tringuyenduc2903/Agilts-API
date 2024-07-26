<?php

namespace App\Observers;

use App\Enums\OptionStatus;
use App\Models\Option;

class OptionObserver
{
    /**
     * Handle the Option "updated" event.
     */
    public function updated(Option $option): void
    {
        $this->created($option);
    }

    /**
     * Handle the Option "created" event.
     */
    public function created(Option $option): void
    {
        if (
            $option->quantity == 0 &&
            $option->status == OptionStatus::IN_STOCK
        )
            $option->update(['status' => OptionStatus::OUT_OF_STOCK]);
    }
}
