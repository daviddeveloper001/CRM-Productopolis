<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\City;
use App\Models\Customer;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->regexify('[A-Za-z0-9]{60}'),
            'first_name' => $this->faker->firstName(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'is_frequent_customer' => $this->faker->boolean(),
            'city_id' => City::factory(),
        ];
    }
}
