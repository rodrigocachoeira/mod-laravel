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
		return $this->model->first();
	}

	/**
	* Retorna todos os registros ordenados
	*
	* @param $key
	* @param $type
	* @return Illuminate\Database\Eloquent\Collection
	*/
	public function ordered($key, $type)
	{
		return $this->model->orderBy($key, $type)->get();
	}

	/**
	* Retorna o último registro
	* encontrado
	*
	* @return null | Illuminate\Database\Eloquent\Model
	*/
	public function last()
	{
		return $this->model->orderBy('id', 'DESC')->first();
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
		return $this->model->where($key, $value)->get();
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
		return $this->model->where($key, $value)->first();
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
		$builder = $this->model;
		array_walk($where, function ($value, $key) use (&$builder) {
			$builder = $builder->where($key, $value);
		});

		return $builder->get();
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
		$builder = $this->model;
		array_walk($where, function ($value, $key) use (&$builder) {
			$builder = $builder->where($key, $value);
		});

		return $builder->first();
	}

	/**
	* Realiza a coleta dos dados de forma paginada
	*
	* @param int $paginate
	* @return Illuminate\Pagination\LengthAwarePaginator
	*/
	public function paginate($paginate = 10)
	{
		return $this->model->paginate($paginate);
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
		$builder = $this->model;
		array_walk($order, function ($value, $key) use (&$builder) {
			$builder = $builder->orderBy($key, $value);
		});

		return $builder->paginate($paginate);
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
		return $this->model->filter($filter)->get();
	}

	/**
	* Realiza a inserção no banco de dados
	* com base nas informações do modelo proposto
	*
	* @param array $data
	* @return boolean
	*/
	public function save(array $data)
	{
		return $this->model->create($data);
	}

	/**
	* Realiza a atuailização do modelo
	* definido com base em um identificador
	*
	* @param $id
	* @param array $data
	* @return boolean
	*/
	public function update($id, array $data)
	{
		return $this->model->where('id', $id)
			->update($data);
	}

	/**
	* Realiza a exclusão de um registro
	* da base de dados com base no identificador
	*
	* @param $id
	* @return boolean
	*/
	public function delete($id)
	{
		return $this->model->where('id', $id)->delete();
	}

	/**
	* Atualiza as informações com base em
	* um conjunto de condições passadas
	*
	* @param @fields
	* @param @data
	* @return boolean
	*/
	public function updateAt(array $fields, array $data)
	{
		$builder = $this->model;
		array_walk($fields, function ($value, $key) use (&$builder) {
			$builder = $builder->where($key, $value);
		});

		return $builder->update($data);
	}

	/**
	* Remove informações com base em
	* um conjunto de condições passadas
	*
	* @param @fields
	* @return boolean
	*/
	public function deleteAt(array $fields)
	{
		$builder = $this->model;
		array_walk($fields, function ($value, $key) use (&$builder) {
			$builder = $builder->where($key, $value);
		});

		return $builder->delete();
	}

}
