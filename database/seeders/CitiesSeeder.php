<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $cities = file_get_contents(public_path('cities.txt'));
        $cities = preg_split('/\r\n|\r|\n/', $cities);
        foreach ($cities as $city) {
            $explodedCity = explode('|', $city);
            if (count($explodedCity) == 3) {
                City::updateOrCreate([
                    'name' => $explodedCity[0],
                    'lat' => $explodedCity[1],
                    'lon' => $explodedCity[2],
                    'public' => 1,
                ]);
            }
        }
    }
}
