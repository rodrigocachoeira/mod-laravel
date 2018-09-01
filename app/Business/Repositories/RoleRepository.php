<?php

namespace App\Business\Repositories;

use App\Business\Entities\Role;


/**
 * Class RoleRepository
 *
 * @package App\Business\Repositories
 */
class RoleRepository extends AppRepository
{

	/**
	* @param Role $role
	*/
	public function __construct(Role $role)
	{
		$this->model = $role;
	}

}
