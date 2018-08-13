<?php

namespace App\Console\Commands;

use App\Console\Commands\Usefuls\DatabaseUseful;
use Illuminate\Console\Command;
use App\User;

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
            $this->newUser();
        } else {
            $this->error('Please, check your database configuration first.');
        }
    }

    /**
    * Cria um novo usuário
    * par a aplicação
    *
    * @return boolean
    */
    protected function newUser()
    {
        $name = $this->ask('Name: ');
        $email = $this->ask('E-mail: ');
        $password = $this->secret('Password: ');

        if (User::create(compact('name', 'email', 'password'))) {
            $this->info(sprintf('%s has been created!', $name));
        } else {
            $this->error('Could not create User.');
        }
    }
}
