<?php
namespace Database\Factories;

use Tests\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'postcode'   => $this->faker->postcode,
            'address'    => $this->faker->address,
            'latitude'   => $this->faker->latitude,
            'longitude'  => $this->faker->longitude,
            'color'      => $this->faker->hexColor,
            'start_at'   => $this->faker->dateTime,
            'end_at'     => $this->faker->dateTime,
        ];
    }
}
