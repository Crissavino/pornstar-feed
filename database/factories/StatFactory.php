<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stat>
 */
class StatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'attribute_id' => $this->faker->numberBetween(1, 10000),
            'subscriptions' => $this->faker->numberBetween(1, 10000),
            'monthly_searches' => $this->faker->numberBetween(1, 10000),
            'views' => $this->faker->numberBetween(1, 10000),
            'videos_count' => $this->faker->numberBetween(1, 10000),
            'premium_videos_count' => $this->faker->numberBetween(1, 10000),
            'white_label_video_count' => $this->faker->numberBetween(1, 10000),
            'rank' => $this->faker->numberBetween(1, 10000),
            'rank_premium' => $this->faker->numberBetween(1, 10000),
            'rank_wl' => $this->faker->numberBetween(1, 10000),
        ];
    }
}
