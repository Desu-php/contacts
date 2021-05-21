<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\PersonInfo;
use App\Models\PersonInfoValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonInfoValueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonInfoValue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'person_info_id' => PersonInfo::all()->random(),
            'person_id' => Person::all()->random(),
            'value' => $this->faker->name
        ];
    }
}
