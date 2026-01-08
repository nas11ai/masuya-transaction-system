<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountTypeFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['percentage', 'fixed']);

        $discountNames = [
            'Member Discount',
            'Early Bird',
            'Flash Sale',
            'Clearance',
            'Seasonal Promo',
            'Bulk Purchase',
            'Loyalty Reward',
            'First Timer'
        ];

        return [
            'code' => strtoupper(fake()->unique()->bothify('DISC###')),
            'name' => fake()->randomElement($discountNames) . ' ' . fake()->numberBetween(5, 50),
            'type' => $type,
            'value' => $type === 'percentage'
                ? fake()->randomFloat(2, 5, 50) // 5% - 50%
                : fake()->randomFloat(2, 10000, 500000), // Rp 10k - 500k
            'description' => fake()->sentence(),
            'is_active' => fake()->boolean(90),
        ];
    }

    public function percentage(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'percentage',
            'value' => fake()->randomFloat(2, 5, 50),
        ]);
    }

    public function fixed(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'fixed',
            'value' => fake()->randomFloat(2, 10000, 500000),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
