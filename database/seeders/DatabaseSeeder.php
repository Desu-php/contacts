<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\City;
use App\Models\Company;
use App\Models\ContactType;
use App\Models\File;
use App\Models\Person;
use App\Models\PersonActivity;
use App\Models\PersonCity;
use App\Models\PersonCompany;
use App\Models\PersonContact;
use App\Models\PersonFile;
use App\Models\PersonInfo;
use App\Models\PersonInfoValue;
use App\Models\PersonLink;
use App\Models\PersonNote;
use App\Models\PersonPhone;
use App\Models\PersonTag;
use App\Models\Tag;
use App\Models\User;
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

        $this->call([
            CitiesSeeder::class,
            ActivitySeeder::class
        ]);
    }
}
