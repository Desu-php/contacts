<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = File::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'size' => $this->faker->numberBetween(1, 100000),
            'path' => $this->faker->filePath(),
            'type' => $this->faker->fileExtension,
        ];
    }
}
