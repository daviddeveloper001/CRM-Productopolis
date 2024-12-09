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
        ReturnAlert::updateOrCreate([
            'type' => 'Alto',
        ]);

        ReturnAlert::updateOrCreate([
            'type' => 'Medio',
        ]);

        ReturnAlert::updateOrCreate([
            'type' => 'Bajo',
        ]);
    }
}
