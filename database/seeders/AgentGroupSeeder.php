<?php

namespace Database\Seeders;

use App\Models\AgentGroup;
use Illuminate\Database\Seeder;

class AgentGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'id' => 1,
                'name' => 'Corporate Platinum',
                'discount_percent' => 5.00,
                'credit_limit' => 500000.00,
                'description' => 'High volume consolidators & wholesale tour operators',
            ],
            [
                'id' => 2,
                'name' => 'Gold Partner',
                'discount_percent' => 3.50,
                'credit_limit' => 250000.00,
                'description' => 'Established IATA & DTS certified retail agents',
            ],
            [
                'id' => 3,
                'name' => 'Silver Tier',
                'discount_percent' => 2.00,
                'credit_limit' => 100000.00,
                'description' => 'Mid-sized growing agencies on standard terms',
            ],
            [
                'id' => 4,
                'name' => 'Bronze Starter',
                'discount_percent' => 0.00,
                'credit_limit' => 0.00,
                'description' => 'Newly approved agencies on prepaid terms',
            ],
        ];

        foreach ($groups as $group) {
            AgentGroup::updateOrCreate(['id' => $group['id']], $group);
        }
    }
}
