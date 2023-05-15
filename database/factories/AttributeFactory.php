<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attribute>
 */
class AttributeFactory extends Factory
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
            'hair_color' => $this->faker->colorName,
            'ethnicity' => $this->faker->country,
            'tattoos' => $this->faker->boolean,
            'piercings' => $this->faker->boolean,
            'breast_size' => $this->faker->numberBetween(24, 80),
            'breast_type' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F', 'G']),
            'gender' => $this->faker->randomElement(['male', 'female', 'm2f', 'f2m']),
            'orientation' => $this->faker->randomElement(['straight', 'gay', 'bisexual']),
            'age' => $this->faker->numberBetween(18, 60),
        ];
    }
}
