<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Person;
use App\Models\PersonCity;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonCityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonCity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'city_id' => City::all()->random(),
            'person_id' => Person::all()->random()
        ];
    }
}
