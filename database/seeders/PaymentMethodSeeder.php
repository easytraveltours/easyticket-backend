<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Meezan Bank (Islamic Corporate)',
                'account_title' => 'EASYTICKET PRIVATE LIMITED',
                'account_number' => '02010103445566',
                'iban' => 'PK36MEZN0002010103445566',
                'bank_name' => 'Meezan Bank Ltd',
                'branch_code' => '0201',
                'instructions' => 'Transfer via online banking or Raast. Mention Booking Code in remarks.',
                'display_order' => 1,
            ],
            [
                'name' => 'Habib Bank Limited (HBL)',
                'account_title' => 'EASYTICKET B2B NETWORK',
                'account_number' => '12347900889901',
                'iban' => 'PK44HABB0012347900889901',
                'bank_name' => 'Habib Bank Limited',
                'branch_code' => '1234',
                'instructions' => 'Instant transfer via HBL Konnect, 1Link or Raast ID.',
                'display_order' => 2,
            ],
            [
                'name' => 'JazzCash Business Merchant',
                'account_title' => 'EASYTICKET TRAVEL CORP',
                'account_number' => '03001234567',
                'iban' => null,
                'bank_name' => 'JazzCash / Mobilink',
                'branch_code' => null,
                'instructions' => 'Send to JazzCash Till / Merchant ID 887123. Retain TID.',
                'display_order' => 3,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(['account_number' => $method['account_number']], $method);
        }
    }
}
