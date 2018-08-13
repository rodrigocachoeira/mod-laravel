<?php

namespace App\Console\Commands\Usefuls;

use PDOException;
use Exception;
use PDO;
use DB;

/**
* Trait responsável por fornecer
* métodos relavantes referente ao banco de
* dados da aplicação
*
* @author Rodrigo Cachoeira
* @package app.console.commands.usefuls
* @version 1.0
*/
trait DatabaseUseful
{

	/**
	* Informações de conexão incorretas
	*
	* @var number
	**/
	protected $badConnectionCode = 1045;

	/**
	* Base de Dados não encontrada
	*
	* @var number
	*/
	protected $databaseNotFoundCode = 1049;

	/**
	* Código recebido por um possível
	* erro ao tentar realizar a conexão
	*
	* @var number
	*/
	protected $pdoErrorCode;

	/**
	* Verifica se o erro gerado ao tentar
	* conectar com o banco de dados é referente
	* a não existência do banco de dados
	*
	* @return boolean
	*/
	protected function databaseNotExist()
	{
		return $this->pdoErrorCode == $this->databaseNotFoundCode;
	}

	/**
	* Retorna todas as tabelas registradas
	* na aplicação
	*
	* @return array
	*/
	protected function tables()
	{
		$tables = array_map('reset', DB::select('SHOW TABLES;'));
		return array_values(array_diff($tables,
			['migrations', 'password_resets']));
	}

	/**
	* Retorna o nome da base de dados
	* configurada na aplicação
	*
	* @return string
	*/
	protected function getDatabaseName()
	{
		return env('DB_DATABASE');
	}

    /**
    * Retorna a conexao PDO de acordo com
    * os dados configurados pela aplicação
    *
    * @return PDO
    */
    protected function getConnection()
    {
    	return new PDO(sprintf('mysql:host=%s;port=%d;',
    		env('DB_HOST'), env('DB_PORT')), env('DB_USERNAME'), env('DB_PASSWORD'));
    }

	/**
    * Verfiica se a base de dados
    * está configurada corretamente
    *
    * @return boolean
    */
    protected function databaseIsOk($showErrors = false)
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (Exception $exception) {
        	$this->pdoErrorCode = $exception->getCode();
            if ($showErrors)
                $this->error($exception->getMessage());
            return false;
        }
    }

}
