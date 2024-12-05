<?php

namespace Database\Seeders;

use App\Models\Segmentation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SegmentionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Segmentation::create([
            'type' => 'Oro',
        ]);

        Segmentation::create([
            'type' => 'Plata',
        ]);

        Segmentation::create([
            'type' => 'Bronce',
        ]);
    }
}
