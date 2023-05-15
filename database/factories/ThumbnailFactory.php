<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thumbnail>
 */
class ThumbnailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'pornstar_id' => $this->faker->numberBetween(1, 10000),
            'height' => $this->faker->numberBetween(300, 400),
            'width' => $this->faker->numberBetween(300, 400),
            'type' => $this->faker->randomElement(['pc', 'mobile', 'tablet']),
            'url' => 'https://via.placeholder.com/350',
        ];
    }
}
