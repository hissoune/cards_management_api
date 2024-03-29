<?php

namespace Database\Factories;

use App\Models\BusinessCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusinessCard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'company' => $this->faker->company,
            'title' => $this->faker->jobTitle,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
