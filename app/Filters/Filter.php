<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Usefuls\StringTrait;

/**
 * Class Filters
 *
 * Classe de definição de alguns
 * método que serão úteis no controle
 * de filtros de entidades
 *
 * @package App\Filters
 * @author Laracasts
 * @author Rodrigo Cachoeira <rodrigocachoeira11@gmail.com>
 * @version 1.0
 */
abstract class Filter
{

    use StringTrait;

    /**
    * @var string
    */
    const MYSQL_LIKE = 'LIKE';

    /**
    * @var string
    */
    const PGSQL_LIKE = 'iLIKE';

    /**
     * @var object
     */
    protected $request, $builder;

    /**
     * @var array
     */
    protected $filters, $orders, $fixed, $columns, $dependencies = [];

    /**
     * ThreadsFilters constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param array $dependencies
     * @return $this
     */
    public function with(array $dependencies = [])
    {
        $this->dependencies = $dependencies;
        return $this;
    }

    /**
     * Verifica qual o banco de dados
     * que está sendo utilizado
     * e configura o element LIKE
     * de acordo com ele
     *
     * @return string
     */
    public function likePattern ()
    {
        return env('DB_CONNECTION') == 'public' ? self::PGSQL_LIKE : self::MYSQL_LIKE;
    }

    /**
     * Realiza as chamadas dos métodos de
     * filtro da entidade
     *
     * @param $builder
     * @return mixed
     */
    public function apply($builder)
    {
        $this->builder = $builder;
        $this->appyFilters()->insertDependencies()
            ->applyColumns()->applyFixed()->applyOrders();

        return $this->builder;
    }

    /**
    * Define as colunas que devem
    * retornar da consulta sql
    *
    * @return $this
    */
    private function applyColumns()
    {
        if (count($this->columns ?? []) > 0)
            $this->builder->select($this->columns);

        return $this;
    }

    /**
    * Aplica a ordenação da coleta
    * de informação dos registros
    *
    * @return $this
    */
    private function applyOrders()
    {
        foreach ($this->orders ?? [] as $column => $direction) {
            $this->builder->orderBy($column, $direction);
        }

        return $this;
    }

    /**
    * Aplica os filtros fixados
    * pela classe independente de
    * existirem filtros na requisição ou não
    *
    * @return $this
    */
    private function applyFixed()
    {
        foreach ($this->fixed ?? [] as $filter) {
            if (method_exists($this, $filter)) {
                $this->$filter();
            }
        }
        return $this;
    }

    /**
    * Aplica os filtros definidos
    * pelo aplicação
    *
    * @return $this
    */
    private function appyFilters()
    {
        foreach ($this->getFilters() as $filter => $value){
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }else {
                $this->genericLike($filter, $value);
            }
        }

        return $this;
    }

    /**
     * Quando a funcao nao é encontrada aplica-se
     * o método like
     *
     * @param $value
     */
    private function genericLike ($column, $value)
    {
        $this->builder->where($this->uppercaseToUnderline($column), $this->likePattern(), '%'.$value.'%');
    }

    /**
     * Realiza uma consulta de registros
     * juntamente com sua dependências
     *
     * @return mixed
     */
    public function insertDependencies ()
    {
        foreach ($this->dependencies ?? [] as $dependency) {
            $this->builder->with($dependency);
        }
        return $this;
    }

    /**
    * Retorna of filtros encontrados
    * na requisição e que estão pré-definidos
    * pelo filtro
    *
     * @return array
     */
    public function getFilters()
    {
        return collect($this->request->all())
            ->intersectByKeys(collect($this->filters)->flip());
    }

    /**
     * Retorna o schema informado como
     * parâmetro, se e somente se a aplicação
     * estiver com o banco de dados PGSQL
     *
     * @param string $schema
     * @return string
     */
    public function getSchema (string $schema)
    {
        if (config('app.env') === 'testing') {
            return '';
        }
        return $schema.'.';
    }

}
