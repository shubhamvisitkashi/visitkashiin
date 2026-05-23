<?php

namespace App\Observers;

use App\Models\BookingPayment;
use App\Models\PaymentAccount;

class BookingPaymentObserver
{
    /**
     * Handle the BookingPayment "created" event.
     *
     * @param  \App\Models\BookingPayment  $bookingPayment
     * @return void
     */
    public function created(BookingPayment $bookingPayment)
    {
        // Add amount to payment account balance (incoming payment)
        if ($bookingPayment->payment_account_id) {
            $account = PaymentAccount::find($bookingPayment->payment_account_id);
            if ($account) {
                $account->increment('current_balance', $bookingPayment->amount);
            }
        }
    }

    /**
     * Handle the BookingPayment "deleted" event.
     *
     * @param  \App\Models\BookingPayment  $bookingPayment
     * @return void
     */
    public function deleted(BookingPayment $bookingPayment)
    {
        // Deduct amount from payment account balance (refund/reversal)
        if ($bookingPayment->payment_account_id) {
            $account = PaymentAccount::find($bookingPayment->payment_account_id);
            if ($account) {
                $account->decrement('current_balance', $bookingPayment->amount);
            }
        }
    }
}
