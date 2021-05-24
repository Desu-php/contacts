<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'user_id' => User::all()->random(),
            'givenName' => $this->faker->firstName,
            'familyName' => $this->faker->lastName,
            'middleName' => $this->faker->firstName,
            'moreNo' => $this->faker->boolean,
            'reminderCall' => $this->faker->numberBetween(1, 10000000),
            'removed' => $this->faker->boolean,
            'thumbnailImage' => $this->faker->imageUrl()
        ];
    }
}
