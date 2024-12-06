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
        PaymentMethod::create([
            'name' => 'Contra Entrega',
        ]);

        PaymentMethod::create([
            'name' => 'Transferencia Bancaria',
        ]);

        PaymentMethod::create([
            'name' => 'Wompi',
        ]);

        PaymentMethod::create([
            'name' => 'Addi',
        ]);

        PaymentMethod::create([
            'name' => 'Sistecredito',
        ]);
    }
}
