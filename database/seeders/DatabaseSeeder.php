<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PSpell\Config;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ConfigSeeder::class,
            PaymentMethodSeeder::class,
            ReturnAlertSeeder::class,
            UserSeeder::class,
        ]);
    }
}
