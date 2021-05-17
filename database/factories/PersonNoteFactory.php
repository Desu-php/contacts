<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Person;
use App\Models\PersonNote;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonNoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonNote::class;

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
            'file_id' => File::all()->random(),
            'note' => $this->faker->name,
            'type' => $this->faker->fileExtension,
            'protected' => $this->faker->boolean,
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->longitude,
        ];
    }
}
