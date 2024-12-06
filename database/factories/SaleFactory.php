<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Sale;

class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_date' => $this->faker->date(),
            'last_order_date_delivered' => $this->faker->date(),
            'total_sales' => $this->faker->randomFloat(2, 0, 99999999.99),
            'total_revenues' => $this->faker->randomFloat(2, 0, 99999999.99),
            'orders_number' => $this->faker->numberBetween(-10000, 10000),
            'number_entries' => $this->faker->numberBetween(-10000, 10000),
            'returns_number' => $this->faker->numberBetween(-10000, 10000),
            'return_value' => $this->faker->randomFloat(2, 0, 99999999.99),
            'last_days_purchase_days' => $this->faker->numberBetween(-10000, 10000),
            'last_item_purchased' => $this->faker->regexify('[A-Za-z0-9]{60}'),
            'customer_id' => Customer::factory(),
            'shop_id' => ::factory(),
            'seller_id' => ::factory(),
            'method_id' => $this->faker->randomNumber(),
            'segmentation_id' => ::factory(),
            'return_alert_id' => ::factory(),
            'payment_method_id' => PaymentMethod::factory(),
        ];
    }
}
