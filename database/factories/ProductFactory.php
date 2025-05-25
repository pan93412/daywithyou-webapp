<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $productFigureIndex = rand(1, 7);

        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'slug' => $this->faker->slug(),
            'price' => $this->faker->randomFloat(2, max: 1000),
            'figure' => "https://daywithyou-cdn.pan93.com/product{$productFigureIndex}.jpg",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
