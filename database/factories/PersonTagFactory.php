<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\PersonTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonTagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonTag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'person_id' => Person::all()->random(),
            'tag' => $this->faker->name,
        ];
    }
}
