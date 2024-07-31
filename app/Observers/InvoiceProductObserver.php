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

        $option->update([
            'quantity' => $option->quantity - $invoice_product->amount,
        ]);
    }
}
