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
            'type' => 'Alto',
        ]);

        ReturnAlert::create([
            'type' => 'Medio',
        ]);

        ReturnAlert::create([
            'type' => 'Bajo',
        ]);
    }
}
