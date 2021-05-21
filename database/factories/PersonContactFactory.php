<?php

namespace Database\Factories;

use App\Models\ContactType;
use App\Models\Person;
use App\Models\PersonContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonContact::class;

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
            'contact_type_id' => ContactType::all()->random(),
            'value' => $this->faker->phoneNumber
        ];
    }
}
