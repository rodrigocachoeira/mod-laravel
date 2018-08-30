<?php

namespace App\Business\Entities;

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
* Classe responsável por definir alguns parâmetros
* e configurações que serão atribuídas a todo model
* criado na aplicação
*
* @author Rodrigo Cachoeira <rodrigocachoeira11@gmail.com>
* @package app
*/
abstract class Model extends EloquentModel
{

	 /**
     * Permite a inclusão dos filtros
     * a entidade
     *
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
