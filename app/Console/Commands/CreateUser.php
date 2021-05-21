<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user';

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
        User::updateOrCreate(
            [
                'name' => 'jabor',
                'phone' => '89998825337',
            ],
            [
                'password' => Hash::make('Passw0rd')
            ]
        );

        User::updateOrCreate(
            [
                'name' => 'softJet',
                'phone' => '79097139938',
            ],
            [
                'password' => Hash::make('Passw0rd')
            ]
        );


        User::updateOrCreate(
            [
                'name' => 'mobile',
                'phone' => '+79659703070',
            ],
            [
                'password' => Hash::make('Passw0rd')
            ]
        );


        return 0;
    }
}
