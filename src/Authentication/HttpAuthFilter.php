<?php

namespace Iv\Framework\Authentication;

use Iv\Framework\Injection\Annotation\Component;
use Iv\Framework\Routing\Filter;
use Iv\Framework\Routing\FilterChain;

/** @Component() */
class HttpAuthFilter extends AuthenticationFilter implements Filter  {
	public function apply($route, FilterChain $chain) {
		try {
			if(empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']))
				throw new AccessDeniedException("Credentials required");

			$this->checkAccess($route, $this->service->login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'], false));
			return $chain->next($route);
		} catch (AccessDeniedException $e) {
			header('WWW-Authenticate: Basic realm="Application"');
			header('HTTP/1.0 401 Unauthorized');

			return $chain->next([
					'controller' => 'ErrorController',
					'method' => 'forbidden'
			]);
		}
	}

	public function requires() {
		return [];
	}
}