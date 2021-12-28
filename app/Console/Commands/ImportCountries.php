<?php

namespace App\Console\Commands;

use App\Imports\CountriesImport;
use App\Models\City;
use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ImportCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rows = Excel::toArray(new CountriesImport(), public_path('countries.xlsx'));
        $countries = [];
        foreach ($rows[0] as $index => $row) {
            if ($index == 0 || empty($row[3])) {
                continue;
            }

            $name = Str::lower($row[3]);
            $countries[$name] = [
                'english_name' => $row[0],
                'lat' => $row[1],
                'lon' => $row[2],
                'name' => $name,
            ];
        }
        foreach ($rows[1] as $index => $row) {
            if ($index == 0) {
                continue;
            }

            $name = Str::lower($row[0]);

            $data = [
                'full_name' => $row[1] ?? null,
                'alpha2' => $row[3],
                'alpha3' => $row[4],
                'iso' => $row[5],
                'part_world' => $row[6] ?? null,
                'location' => $row[7] ?? null,
            ];

            if (isset($countries[$name])) {
                $countries[$name] = array_merge($countries[$name], $data);
            } else {
                $data['name'] = $name;
                $data['lat'] = 0;
                $data['lon'] = 0;
                $countries[$name] = $data;
            }

        }


        foreach ($countries as $country) {
            City::firstOrCreate([
                'name' => $country['name']
            ], [
                'public' => 1,
                'lat' => $country['lat'],
                'lon' => $country['lon']
            ]);
        }
        return 0;
    }
}
