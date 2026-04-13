<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $discounts = [
            [
                'code'       => 'WELCOME10',
                'type'       => 'percent',
                'value'      => 10,
                'min_amount' => 0,
                'max_uses'   => null,
                'uses'       => 0,
                'is_active'  => true,
                'expires_at' => null,
            ],
            [
                'code'       => 'SUMMER20',
                'type'       => 'percent',
                'value'      => 20,
                'min_amount' => 50,
                'max_uses'   => 100,
                'uses'       => 0,
                'is_active'  => true,
                'expires_at' => now()->addMonths(3),
            ],
            [
                'code'       => 'SAVE5',
                'type'       => 'fixed',
                'value'      => 5,
                'min_amount' => 30,
                'max_uses'   => null,
                'uses'       => 0,
                'is_active'  => true,
                'expires_at' => null,
            ],
        ];

        foreach ($discounts as $discount) {
            Discount::firstOrCreate(['code' => $discount['code']], $discount);
        }
    }
}
