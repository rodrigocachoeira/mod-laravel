<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Classe responsável por manter salvo na sessão
 * os parâmetros informados e vinculados com o path
 *
 * @author Rodrigo Cachoeira <rodrigocachoeira11@gmail.com>
 * @package App\Http\Middleware
 * @version 2.0
 */
class SessionRetrieveMiddleware
{

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if ($redirect = $this->listener()) {
			return $redirect;
		}
		return $next($request);
	}

    /**
    * Verifica se existem parâmetros na
    * url para serem salvos
    *
    * @return Redirect | false
    */
	private function listener() {
		$url = $this->params();
		if ($url['params'] == 'previous') {
			return redirect($this->retrieveCache($url));
		}
		if (request()->isMethod('get')) {
			session()->put($url['path'], $url['params']);
		}
		return false;
	}

	/**
	 * Retorna os parâmetros de uma url
	 * caso ela possua um histórico salvo
	 * no cache
	 *
	 * @param $url
	 * @return string
	 */
	public function retrieveCache($url) {
		$url = is_array($url) ? $url['path'] : $url;
		if (session()->has($url)) {
			return $url . '?' . session()->get($url);
		}
		return $url;
	}

	/**
	 * Retorna todos os parâmetros da url
	 * juntamente com o seu path
	 *
	 * @return array
	 */
	private function params() {
		$url = request()->getRequestUri();
		return [
			'path' => explode('?', $url)[0],
			'params' => explode('?', $url)[1] ?? '',
		];
	}
}
