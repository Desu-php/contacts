<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $activities = file_get_contents(public_path('foa.txt'));
        $activities = preg_split('/\r\n|\r|\n/', $activities);
        foreach ($activities as $activity) {
            Activity::updateOrCreate([
                'name' => $activity,
                'public' => 1,
            ]);
        }

    }
}
