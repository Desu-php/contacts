<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Model;
use App\Models\Person;
use App\Models\PersonFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonFile::class;

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
            'type' => $this->faker->name
        ];
    }
}
