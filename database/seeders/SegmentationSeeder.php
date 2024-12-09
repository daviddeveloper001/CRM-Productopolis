<?php

namespace Database\Seeders;

use App\Models\Segmentation;
use Illuminate\Database\Seeder;

class SegmentationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Segmentation::updateOrCreate([
            'type' => 'Oro',
        ]);

        Segmentation::updateOrCreate([
            'type' => 'Plata',
        ]);

        Segmentation::updateOrCreate([
            'type' => 'Bronce',
        ]);
    }
}
