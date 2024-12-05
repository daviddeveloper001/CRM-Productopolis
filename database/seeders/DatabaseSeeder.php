<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CitySeeder::class,
            DepartmentSeeder::class,
            ShopSeeder::class,
            PaymentMethodSeeder::class,
            ReturnAlertSeeder::class,
            ShopSeeder::class,
            SellerSeeder::class,
            UserSeeder::class,
            SegmentionSeeder::class,
        ]);
    }
}
