<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:score';

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
        $users = User::all();

        $startMonth = Carbon::now()->addMonths(-4)->startOfMonth()->toDateString();
        $lastMonth = now()->addMonth()->format('Y-m');

        $scores = [
            ['score' => 1, 'request_score' => 1],
            ['score' => 1, 'request_score' => 2],
            ['score' => 2, 'request_score' => 4],
            ['score' => 1, 'request_score' => 5],
            ['score' => 3, 'request_score' => 8],
        ];

        foreach ($users as $user){
            $month = Carbon::parse($startMonth)->format('Y-m');
            $i = 0;
            while ($month != $lastMonth){
                $user->scores()->create(array_merge($scores[$i], ['created_at' => Carbon::parse($month)->toDateString()] ));
                $month = Carbon::parse($month)->addMonth()->format('Y-m');
                $i++;
            }
        }
    }
}
