<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $productTypes = [
            'Laptop',
            'Mouse',
            'Keyboard',
            'Monitor',
            'Headset',
            'Webcam',
            'SSD',
            'RAM',
            'Motherboard',
            'Processor'
        ];

        $brands = [
            'Asus',
            'Acer',
            'Lenovo',
            'HP',
            'Dell',
            'Logitech',
            'Razer',
            'Corsair',
            'Kingston',
            'Samsung'
        ];

        $productType = fake()->randomElement($productTypes);
        $brand = fake()->randomElement($brands);

        return [
            'code' => strtoupper(fake()->unique()->bothify('PROD###??')),
            'name' => "{$brand} {$productType} " . fake()->randomNumber(4),
            'price' => fake()->randomFloat(2, 100000, 50000000),
            'stock' => fake()->numberBetween(0, 100),
            'description' => fake()->sentence(10),
            'is_active' => fake()->boolean(90), // 90% active
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock' => fake()->numberBetween(0, 5),
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock' => 0,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
