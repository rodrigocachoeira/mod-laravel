<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Usefuls\DatabaseUseful;

/**
* Create users for application
*
* @author Rodrigo Cachoeira
* @package app.console.commands
* @version 1.0
*/
class CreateUserCommand extends Command
{

    use DatabaseUseful;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create
                            {error? : Show errors}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a New User for Application';

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
        if ($this->databaseIsOk(! is_null($this->argument('error')))) {
            // TODO: do awesome things here
        } else {
            $this->error('Please, check your database configuration first.');
        }
    }
}
