<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ReturnAlert;

class ReturnAlertFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReturnAlert::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(["Top","Medium","Low"]),
        ];
    }
}
