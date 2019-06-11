<?php

namespace Syahrulyusuf97\Larator\Commands;

use Illuminate\Console\Command;
use App\Console\Controllers\CreateDashboard;

class GenerateDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dashboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating dashboard and login';

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
        $controller = new CreateDashboard(); // make sure to import the controller
        $this->info($controller->store());
    }
}
