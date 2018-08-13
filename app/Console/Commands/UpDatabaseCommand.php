<?php

namespace App\Console\Commands;

use App\Console\Commands\Usefuls\DatabaseUseful;
use Illuminate\Console\Command;
use PDOException;
use Artisan;
use DB;

/**
* Sobe o banco de dados caso ele ainda esteja
* indisponível
*
* @author Rodrigo Cachoeira
* @package app.console.commands
* @version 1.0
*/
class UpDatabaseCommand extends Command
{

    use DatabaseUseful;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Application Configuration and install database.';

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
    * Cria o banco de dados
    * caso ainda não tenha sido criado
    *
    * @return boolean
    */
    private function createDatabase()
    {
        try {
            $sql = 'CREATE DATABASE IF NOT EXISTS %s;';
            $this->getConnection()->exec(sprintf($sql, env('DB_DATABASE')));
            $this->info('Create Database.');

            return true;
        } catch (PDOException $exception) {
            return false;
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! $this->databaseIsOk()) {
            if ($this->databaseNotExist()) {
                $this->createDatabase();
            } else {
               return $this->error('Please, check your database configuration first.');
            }
        }
        $this->createSchemas()->migrate()->seed();
        $this->info('Installation Completed!');
    }

    /**
    * Verifica se o banco de dados já
    * possui dados cadastrados
    *
    * @return boolean
    */
    protected function hasSomeDataAlready()
    {
        foreach ($this->tables() as $table) {
            if ($table !== 'migrations') {
                if (count(DB::select(sprintf('SELECT * FROM %s', $table))) > 0)
                    return true;
            }
        }
        return false;
    }

    /**
    * Cria as seeds no banco de dados
    *
    * @return $this
    */
    private function seed()
    {
        $ask = $this->ask('You want create seeds? (y/n)');
        if ($ask === 'y') {
            if ($this->hasSomeDataAlready()) {
                $realy = $this->ask('Apparently your database already has records, you still want to use the seeder? (y/n)');

                if ($realy !== 'y')
                    return $this;
            }
            Artisan::call('db:seed');
        }
        return $this;
    }

    /**
    * Realiza as migrações configuradas
    *
    * @return boolean
    */
    protected function migrate()
    {
        try {
            $this->info('Begin of Migrations');
            Artisan::call('migrate');
            $this->info('End of Migrations');

            return $this;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
    * Caso o banco de dados possua schemas
    * este método irá criá-los
    *
    * @return $this
    */
    protected function createSchemas()
    {
        return $this;
    }
}
