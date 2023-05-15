<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pornstar>
 */
class PornstarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'feed_id' => $this->faker->numberBetween(1, 100),
            'name' => $this->faker->name,
            'license' => $this->faker->randomElement(['REGULAR', 'PREMIUM']),
            'wl_status' => $this->faker->boolean,
            'link' => $this->faker->url,
            // aliases is a json with different alias for this pornstar, like this ["Aliyah Julie","Aliyah Jolie","Aaliyah","Macy"] or []
            'aliases' => $this->faker->randomElement([json_encode($this->faker->words(4)), '[]']),
        ];
    }
}
