<?php

namespace Database\Factories;

use App\Models\Marker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Marker>
 */
class MarkerFactory extends Factory
{
    protected $model = Marker::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => Marker::TYPE_PLACE,
            'status' => Marker::STATUS_ACTIVE,
            'title' => fake()->sentence(3),
            'address' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
