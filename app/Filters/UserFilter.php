<?php

namespace App\Filters;

/**
* Classe de filtro genérica de usuários
*
* @author Rodrigo Cachoeira
* @package App\Filters
**/
class UserFilter extends Filter
{

	/**
	* @var array
	*/
	protected $filters = [
		'name', 'email', 'role_id'
	];

	/**
	* @var array
	*/
	protected $orders = [
		'name' => 'ASC'
	];

    /**
     * @param $value
     */
	public function role_id($value)
    {
        if (! is_null($value))
            $this->builder->where('role_id', $value);
    }

}
