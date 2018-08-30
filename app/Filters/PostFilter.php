<?php

namespace App\Filters;

/**
* Classe de filtro genérica de posts
*
* @author Rodrigo Cachoeira
* @package App\Filters
**/
class PostFilter extends Filter
{

	/**
	* @var array
	*/
	protected $filters = [
		'title'
	];

	/**
	* @var array
	*/
	protected $orders = [
		'title' => 'ASC'
	];

	/**
	* @var array
	*/
	protected $columns = [
		'title'
	];

}
