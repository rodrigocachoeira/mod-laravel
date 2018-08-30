<?php

namespace App\Business\Repositories;

use App\Filters\Filter;

/**
* Classe cos métodos
* de interação com o banco de dados
* dos repositórios
*
* @author Rodrigo Cachoeira
* @package App\Business\Repositories
* @version 2.0
*/
abstract class AppRepository implements RepositoryInterface
{

	/**
	* @var Illuminate\Database\Eloquent\Model
	*/
	public $model;

	/**
	* Retorna todos os registros
	*
	* @return Illuminate\Database\Eloquent\Collection
	*/
	public function all()
	{
		return $this->model->get();
	}

	/**
	* Retorna todos os registros de forma decrescente
	* com base no created_at
	*
	* @return Illuminate\Database\Eloquent\Collection
	*/
	public function latest()
	{
		return $this->model->latest()->get();
	}

	/**
	* Retorna um registro com base no id
	*
	* @param string $id
	* @return null | Illuminate\Database\Eloquent\Model
	*/
	public function get($id)
	{
		return $this->model->where('id', $id)->first();
	}

	/**
	* Retorna o primeiro registro
	* encontrado
	*
	* @return null | Illuminate\Database\Eloquent\Model
	*/
	public function first()
	{
		//TODO: here
	}

	/**
	* Retorna o último registro
	* encontrado
	*
	* @return null | Illuminate\Database\Eloquent\Model
	*/
	public function last()
	{
		//TODO: here
	}

	/**
	* Retorna todos os registros que atenderem
	* a condição passada como parâmetro
	*
	* @param string $key
	* @param string $value
	* @return Illuminate\Database\Eloquent\Collection
	*/
	public function getWhere($key, $value)
	{
		//TODO: here
	}

	/**
	* Retorna o primeiro registro que for encontrado
	* que atende as condições passadas como parâmetro
	*
	* @param string $key
	* @param string $value
	* @return null | Illuminate\Database\Eloquent\Model
	*/
	public function getWhereFirst($key, $value)
	{
		//TODO: here
	}

	/**
	* Retorna todos os registros que atenderem
	* as condições passadas como parâmetro
	*
	* @param array $where
	* @return Illuminate\Database\Eloquent\Collection
	*/
	public function getWhereAt(array $where)
	{
		//TODO: here
	}

	/**
	* Retorna todos os registros que atenderem
	* as condições passadas como parâmetro
	*
	* @param array $where
	* @return null | Illuminate\Database\Eloquent\Model
	*/
	public function getWhereAtFirst(array $where)
	{
		//TODO: here
	}

	/**
	* Realiza a coleta dos dados de forma paginada
	*
	* @param int $paginate
	* @return Illuminate\Pagination\LengthAwarePaginator
	*/
	public function paginate($paginate = 10)
	{
		//TODO: here
	}

	/**
	* Realiza a paginação e configura a ordenação
	* da coleção a ser retornada
	*
	* @param int $paginate
	* @param array $order
	* @return Illuminate\Pagination\LengthAwarePaginator
	*/
	public function paginateOrder($paginate = 10, array $order)
	{
		//TODO: here
	}

	/**
	* Realiza uma consulta as registros com base
	* em um filtro específico
	*
	* @param App\Filters\Filter $filter
	* @return Illuminate\Database\Eloquent\Collection
	*/
	public function withFilter(Filter $filter)
	{
		//TODO: here
	}

}
