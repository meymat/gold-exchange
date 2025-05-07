<?php

namespace App\Modules\Trade\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradeTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name'=>'buy',  'description'=>'خرید'],
            ['name'=>'sell', 'description'=>'فروش'],
        ];

        foreach ($items as $data) {
            DB::table('b_trade_types')
                ->updateOrInsert(['name'=>$data['name']], $data);
        }
    }
}
