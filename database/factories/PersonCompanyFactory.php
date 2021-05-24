<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Person;
use App\Models\PersonCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonCompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonCompany::class;

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
            'company_id' => Company::all()->random(),
        ];
    }
}
