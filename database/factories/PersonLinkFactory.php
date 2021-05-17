<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\Personlink;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonLinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Personlink::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'link' => $this->faker->url,
            'name' => $this->faker->name,
            'person_id' => Person::all()->random()
        ];
    }
}
