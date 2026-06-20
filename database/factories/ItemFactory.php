<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_item'      => fake()->words(3, true),
            'description'    => fake()->sentence(),
            'image'          => 'items/default.jpg',
            'location_found' => fake()->city(),
            'date_found'     => fake()->date(),
            'time_found'     => fake()->time(),
            'finder_name'    => fake()->name(),
            'finder_contact' => fake()->phoneNumber(),
            'admin_contact'  => null,
            'status'         => 'pending',
            'user_id'        => User::factory(),
        ];
    }

    /**
     * Set status ke approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Set status ke taken.
     */
    public function taken(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'taken',
        ]);
    }

    /**
     * Set status ke pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }
}
