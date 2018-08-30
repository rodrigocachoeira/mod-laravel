<?php

namespace App\Business\Repositories;

use App\Business\Entities\Post;

/**
* Classe cos métodos de interação com a
* lista de posts da aplicação
*
* @author Rodrigo Cachoeira
* @package App\Business\Repositories
* @version 2.0
*/
class PostRepository extends AppRepository
{

	/**
	* @param Post $post
	*/
	public function __construct(Post $post)
	{
		$this->model = $post;
	}

}
