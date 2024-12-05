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
        ReturnAlert::create([
            'status' => 'Top',
        ]);

        ReturnAlert::create([
            'status' => 'Medium',
        ]);

        ReturnAlert::create([
            'status' => 'Low',
        ]);
    }
}
