<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\PaymentMethod;

class PaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMethod::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'method' => $this->faker->randomElement(["ContraEntrega","TransferenciaBancaria","Wompi","Addi","SisteCredito"]),
        ];
    }
}
