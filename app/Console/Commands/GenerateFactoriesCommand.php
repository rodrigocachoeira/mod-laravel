<?php

namespace App\Console\Commands;

use App\Console\Commands\Usefuls\DatabaseUseful;
use Illuminate\Console\Command;

/**
* Cria factories genéricas
* para as tabelas presentes no
* banco de dados
*
* @author Rodrigo Cachoeira
* @package app.console.commands
* @version 1.0
*/
class GenerateFactoriesCommand extends Command
{

    use DatabaseUseful;

    /**
    * @var Collection
    */
    protected $columns;

    /**
    * @var array
    */
    protected $fields = [
        '' => 'Ignorar',
        'name' => 'Lucy Cechtelar',
        'password' => '$10$fw.y4umsVyTxllO95rkdBevvfAzDALpfIRQtpZ/O1Ic9zXPR/D1GG',
        'cellPhone' => '(47) 99999-9999',
        'phoneNumber' => '(47) 9999-9999',
        'cpf' => '14534334576',
        'cnpj' => '23663478000124',
        'date' => '1979-06-09',
        'time' => '20:49:42',
        'boolean' => 'true',
        'email' => 'tkshlerin@collins.com',
        'text' => 'Dolores sit sint laboriosam dolorem culpa et autem. Beatae nam sunt fugit ...',
        'word' => 'aut',
        'paragraph' => 'Ut ab voluptas sed a nam. Sint autem...',
        'randomDigitNotNull' => '7',
        'randomNumber' => '5454',
        'randomElement' => '[a, b, c]',
        'title' => 'Just a Title',
        'firstName' => 'Marry',
        'lastName' => 'Lee',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'factories:generic';

    /**
    * @var collection
    */
    protected $factoryColumns;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Generic Factories for created tables in database';

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
        $table = $this->whatTable();
        if ($table === 'all') {
            foreach (array_diff($this->whatTable(), ['all']) as $table) {
                $this->createFactoryFile($table);
            }
        } else {
            $this->createFactoryFile($table);
        }
    }

    /**
    * Cria um documento contendo
    * uma factory para para a tabela
    * passada como parâmetro
    *
    * @param string $table
    * @return boolean
    */
    private function createFactoryFile($table)
    {
        $this->columns = collect(); //reset columns for a new table factory
        $this->factoryColumns = collect(); //reset columns for a new table factory

        $this->columns = $this->getColumnsOf($table)->filter(function ($column) {
            return ! in_array($column,
                ['id', 'created_at', 'updated_at', 'remember_token']);
        });

        $this->askFakeTypes($table);
    }

    /**
    * Verifica de modo interativo quais
    * os tipos de faker values que serão
    * atribuídos a factory que será criada
    *
    * @param string $table
    * @return mixed
    */
    protected function askFakeTypes(string $table)
    {
        $this->info(sprintf('%s Table', ucfirst($table)));
        $this->columns->map(function ($column) {
            $ask = !in_array($column, array_keys($this->fields)) ?
                $this->choice($column, $this->fields) : $column;

            $this->factoryColumns->put($column, $ask);
        });

        $this->createFile();
    }

    /**
    * Cria uma factory da tabela em questão
    * contendo as colunas pré definidas
    *
    * @return void
    */
    protected function createFile()
    {
        dd($this->factoryColumns);
    }

    /**
    * Retorna todas as colunas de uma
    * tabela em específico
    *
    * @param string $table
    * @return Collection
    **/
    private function getColumnsOf(string $table)
    {
        $sql = sprintf('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "%s"', $table);

        $columns = collect(array_map(function ($column) {
            return $column->COLUMN_NAME;
        }, \DB::select($sql)))
            ->filter(function ($column) {
            return !in_array($column,
                ['CURRENT_CONNECTIONS', 'TOTAL_CONNECTIONS', 'USER']);
        });
        return $columns;
    }

    /**
    * Requisita uma tabela ao usuário
    * para gerar as factories
    *
    * @return string
    */
    private function whatTable()
    {
        $tables = $this->tables();
        array_unshift($tables, 'all');

        return $this->choice('What table? ', $tables);
    }
}
