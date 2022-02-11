<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'label' => $this->faker->word(),
            'value' => $this->faker->numberBetween(1, 20),
            'currency' => $this->faker->randomElement(Config::get('currencies')),
        ];
    }
}
