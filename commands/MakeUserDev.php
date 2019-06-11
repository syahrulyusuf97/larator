<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Syahrulyusuf97\Larator\src\Http\Controllers\CreateUser;

class MakeUserDev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:userDev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user as Developer';

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
     * @return mixed
     */
    public function handle()
    {
        $controller = new CreateUser(); // make sure to import the controller
        $result = $controller->dev();
        $this->info($result);
    }
}
