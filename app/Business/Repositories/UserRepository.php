<?php

namespace App\Business\Repositories;

use App\Business\Entities\User;

class UserRepository extends AppRepository
{

	/**
	* @param User $user
	*/
	public function __construct(User $model)
	{
		$this->model = $model;
	}

}
