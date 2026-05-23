<?php

namespace App\Observers;

use App\Models\VendorPayment;
use App\Models\PaymentAccount;

class VendorPaymentObserver
{
    /**
     * Handle the VendorPayment "created" event.
     *
     * @param  \App\Models\VendorPayment  $vendorPayment
     * @return void
     */
    public function created(VendorPayment $vendorPayment)
    {
        // Deduct amount from payment account balance
        if ($vendorPayment->payment_account_id) {
            $account = PaymentAccount::find($vendorPayment->payment_account_id);
            if ($account) {
                $account->decrement('current_balance', $vendorPayment->amount);
            }
        }
    }

    /**
     * Handle the VendorPayment "deleted" event.
     *
     * @param  \App\Models\VendorPayment  $vendorPayment
     * @return void
     */
    public function deleted(VendorPayment $vendorPayment)
    {
        // Add amount back to payment account balance
        if ($vendorPayment->payment_account_id) {
            $account = PaymentAccount::find($vendorPayment->payment_account_id);
            if ($account) {
                $account->increment('current_balance', $vendorPayment->amount);
            }
        }
    }
}
