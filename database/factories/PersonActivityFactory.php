<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Person;
use App\Models\PersonActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonActivity::class;

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
            'activity_id' => Activity::all()->random()
        ];
    }
}
