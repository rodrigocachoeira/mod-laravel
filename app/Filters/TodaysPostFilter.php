<?php

namespace App\Filters;

use Carbon\Carbon;

/**
* Filtro que indica que apenas os posts
* de hoje devem ser mostrados
*
* @author Rodrigo Cachoeria <rodrigocachoeira11@gmail.com>
* @package App\Filters
*/
class TodaysPostFilter extends Filter
{

	/**
	* @var array
	*/
	protected $fixed = ['todays'];

	/**
	* Apenas os posts de hoje
	*
	* @return void
	*/
	protected function todays()
	{
		$this->builder->whereDate('created_at', Carbon::today()->toDateString());
	}

}
