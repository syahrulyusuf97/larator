<?php

namespace Syahrulyusuf97\Larator\Commands;

use Illuminate\Console\Command;
use Syahrulyusuf97\Larator\Http\Controllers\CreateAccess;

class MakeAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create menu for larator';

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
        $controller = new CreateAccess(); // make sure to import the controller
        $result = $controller->store();
        $this->info($result);
    }
}
