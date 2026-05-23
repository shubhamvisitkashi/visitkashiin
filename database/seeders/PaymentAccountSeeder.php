<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentAccount;

class PaymentAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = [
            [
                'account_name' => 'Cash Counter',
                'account_type' => 'cash',
                'initial_balance' => 0,
                'current_balance' => 0,
                'is_active' => true,
                'notes' => 'Main cash counter for daily transactions',
            ],
            [
                'account_name' => 'Primary Bank Account',
                'account_type' => 'bank_transfer',
                'account_number' => '1234567890',
                'bank_name' => 'HDFC Bank',
                'branch_name' => 'Main Branch',
                'ifsc_code' => 'HDFC0001234',
                'initial_balance' => 0,
                'current_balance' => 0,
                'is_active' => true,
                'notes' => 'Primary business bank account',
            ],
            [
                'account_name' => 'UPI - Paytm',
                'account_type' => 'upi',
                'account_number' => 'business@paytm',
                'initial_balance' => 0,
                'current_balance' => 0,
                'is_active' => true,
                'notes' => 'Paytm UPI for digital payments',
            ],
            [
                'account_name' => 'UPI - PhonePe',
                'account_type' => 'upi',
                'account_number' => 'business@ybl',
                'initial_balance' => 0,
                'current_balance' => 0,
                'is_active' => true,
                'notes' => 'PhonePe UPI for digital payments',
            ],
        ];

        foreach ($accounts as $account) {
            PaymentAccount::create($account);
        }
    }
}
