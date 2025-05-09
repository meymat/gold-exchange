<?php

namespace Modules\commission\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommissionRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('commission_rules')->insert([
            [
                'from_amount'  => 0.000,
                'to_amount'    => 1.000,
                'percentage'   => 2.0,
                'minimum_fee'  => 50_000,
                'maximum_fee'  => 5_000_000,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'from_amount'  => 1.000,
                'to_amount'    => 10.000,
                'percentage'   => 1.5,
                'minimum_fee'  => 50_000,
                'maximum_fee'  => 5_000_000,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'from_amount'  => 10.000,
                'to_amount'    => null,
                'percentage'   => 1.0,
                'minimum_fee'  => 50_000,
                'maximum_fee'  => 5_000_000,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}
