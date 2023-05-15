<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feed>
 */
class FeedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'site' => $this->faker->url,
            'generation_date' => $this->faker->dateTime,
            'items_count' => $this->faker->numberBetween(15000, 30000),
        ];
    }
}
