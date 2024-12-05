<?php

namespace Database\Seeders;

use App\Models\ReturnAlert;
use Illuminate\Database\Seeder;

class ReturnAlertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReturnAlert::factory()->count(5)->create();
    }
}
