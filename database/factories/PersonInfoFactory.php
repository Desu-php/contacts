<?php

namespace Database\Factories;

use App\Models\PersonInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonInfoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'name' => $this->faker->name,
        ];
    }
}
