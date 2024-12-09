<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::updateOrCreate([
            'name' => 'Contra Entrega',
        ]);

        PaymentMethod::updateOrCreate([
            'name' => 'Transferencia Bancaria',
        ]);

        PaymentMethod::updateOrCreate([
            'name' => 'Wompi',
        ]);

        PaymentMethod::updateOrCreate([
            'name' => 'Addi',
        ]);

        PaymentMethod::updateOrCreate([
            'name' => 'Sistecredito',
        ]);
    }
}
