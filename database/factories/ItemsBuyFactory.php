<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ItemsBuy;

class ItemsBuyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ItemsBuy::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name_item' => $this->faker->regexify('[A-Za-z0-9]{400}'),
        ];
    }
}
