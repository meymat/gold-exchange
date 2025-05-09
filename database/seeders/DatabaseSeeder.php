<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Commission\Database\Seeders\CommissionRuleSeeder;
use Modules\user\app\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call($this->getMainSeeders());
    }


    private function getMainSeeders(): array
    {
        return [
            CommissionRuleSeeder::class,
        ];
    }
}
