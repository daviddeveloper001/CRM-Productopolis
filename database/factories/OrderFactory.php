<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Order;
use App\Models\Shop;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'first_order_date' => $this->faker->date(),
            'last_order_date' => $this->faker->date(),
            'last_order_date_delivered' => $this->faker->date(),
            'seller_id' => ::factory(),
            'shop_id' => Shop::factory(),
            'payment_method_id' => ::factory(),
            'total_order' => $this->faker->randomFloat(2, 0, 99999999.99),
            'total_entries' => $this->faker->randomFloat(2, 0, 99999999.99),
            'total_returns' => $this->faker->randomFloat(2, 0, 99999999.99),
            'total_sales' => $this->faker->randomFloat(2, 0, 99999999.99),
            'total_revenues' => $this->faker->randomFloat(2, 0, 99999999.99),
            'return_value' => $this->faker->randomFloat(2, 0, 99999999.99),
            'days_since_last_purchase' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
