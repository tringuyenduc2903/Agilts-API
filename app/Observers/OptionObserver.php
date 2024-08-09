<?php

namespace App\Observers;

use App\Enums\OptionStatus;
use App\Models\Option;

class OptionObserver
{
    /**
     * Handle the Option "updating" event.
     */
    public function updating(Option $option): void
    {
        $this->creating($option);
    }

    /**
     * Handle the Option "creating" event.
     */
    public function creating(Option $option): void
    {
        if (
            $option->quantity == 0 &&
            $option->getRawOriginal('status') == OptionStatus::IN_STOCK
        )
            $option->status = OptionStatus::OUT_OF_STOCK;
    }
}
