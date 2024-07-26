<?php

namespace App\Observers;

use App\Models\InvoiceProduct;

class InvoiceProductObserver
{
    /**
     * Handle the InvoiceProduct "created" event.
     */
    public function created(InvoiceProduct $invoice_product): void
    {
        $option = $invoice_product->option;

        if ($option->quantity == 0)
            $invoice_product->update(['amount' => 0]);
        else
            $option->update([
                'quantity' => $option->quantity - $invoice_product->amount,
            ]);

        request()->user()->carts
            ->whereOptionId($option->id)
            ->delete();
    }
}
