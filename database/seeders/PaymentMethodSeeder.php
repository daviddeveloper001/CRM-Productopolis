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
            'method' => 'Contra Entrega',
        ]);

        PaymentMethod::create([
            'method' => 'Transferencia Bancaria',
        ]);

        PaymentMethod::create([
            'method' => 'Wompi',
        ]);

        PaymentMethod::create([
            'method' => 'Addi',
        ]);

        PaymentMethod::create([
            'method' => 'Sistecredito',
        ]);
    }
}
