<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\City;
use App\Models\Company;
use App\Models\File;
use App\Models\Person;
use App\Models\PersonActivity;
use App\Models\PersonCity;
use App\Models\PersonCompany;
use App\Models\PersonFile;
use App\Models\PersonLink;
use App\Models\PersonNote;
use App\Models\PersonPhone;
use App\Models\PersonTag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Person::factory(20)->create();
        PersonPhone::factory(50)->create();
        PersonLink::factory(50)->create();
        File::factory(50)->create();
        PersonNote::factory(50)->create();
        PersonFile::factory(50)->create();
        PersonTag::factory(50)->create();
        City::factory(50)->create();
        PersonCity::factory(50)->create();
        Activity::factory(50)->create();
        PersonActivity::factory(50)->create();
        Company::factory(50)->create();
        PersonCompany::factory(50)->create();

    }
}
