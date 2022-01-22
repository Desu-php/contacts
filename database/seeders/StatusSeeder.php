<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\Task;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            ['name' => Task::delivered, 'type' => Task::class,],
            ['name' => Task::completed, 'type' => Task::class,],
            ['name' => Task::not_to_do, 'type' => Task::class,],
            ['name' => Task::postpone_until_tomorrow, 'type' => Task::class,],
            ['name' => Task::delegate_to_assistant, 'type' => Task::class,],
        ];

        foreach ($data as $datum){
            Status::updateOrCreate($datum);
        }
    }
}
