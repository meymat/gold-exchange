<?php


namespace App\Modules\Order\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'open', 'description' => 'سفارش باز'],
            ['name' => 'partially_filled', 'description' => 'پارشال'],
            ['name' => 'filled', 'description' => 'کامل انجام‌شده'],
            ['name' => 'cancelled', 'description' => 'لغو‌شده'],
        ];

        foreach ($items as $data) {
            DB::table('b_order_statuses')
                ->updateOrInsert(['name' => $data['name']], $data);
        }
    }
}
