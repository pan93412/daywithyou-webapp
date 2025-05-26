<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'recipient_name' => $this->faker->name(),
            'recipient_email' => $this->faker->email(),
            'recipient_phone' => $this->faker->phoneNumber(),
            'recipient_address' => $this->faker->streetAddress(),
            'recipient_city' => $this->faker->city(),
            'recipient_zip_code' => $this->faker->postcode(),
            'note' => $this->faker->word(),
            'payment_method' => $this->faker->randomElement(['cash', 'line_pay', 'bank_transfer']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
