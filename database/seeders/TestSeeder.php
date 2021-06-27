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
use App\Models\PersonTag;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Person::factory(20)->create();
        PersonLink::factory(50)->create();
        File::factory(50)->create();
        PersonNote::factory(50)->create();
        PersonFile::factory(50)->create();
        Tag::factory(50)->create();
        PersonTag::factory(50)->create();
        City::factory(50)->create();
        PersonCity::factory(50)->create();
        Activity::factory(50)->create();
        PersonActivity::factory(50)->create();
        Company::factory(50)->create();
        PersonCompany::factory(50)->create();
        ContactType::factory(5)->create();
        PersonContact::factory(50)->create();
        PersonInfo::factory(50)->create();
        PersonInfoValue::factory(50)->create();
    }
}
