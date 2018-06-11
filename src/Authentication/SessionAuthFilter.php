<?php

namespace Iv\Framework\Authentication;

use Iv\Framework\Injection\Annotation\Component;
use Iv\Framework\Output\Redirect;
use Iv\Framework\Routing\Filter;
use Iv\Framework\Routing\FilterChain;
use Iv\Framework\Session\Session;

/** @Component() */
class SessionAuthFilter extends AuthenticationFilter implements Filter  {
	const COOKIE_LIFETIME = 604800;
	const COOKIE_PREFIX = 'IV';

	public function apply($route, FilterChain $chain) {
		// Always make authentication accessible
		if($route['controller'] == 'AuthenticationController')
			return $chain->next($route);

		/** @var Session $session */
		$session = $this->container->get('Session');
		$user = $this->currentUser( $session );

		if(empty($user))
			$user = $this->reloginUser($session);

		try {
			$this->checkAccess($route, $user);
			return $chain->next($route);
		} catch (AccessDeniedException $e) {
			return empty( $user ) ? new Redirect("/login") : $chain->next([
					'controller' => 'ErrorController',
					'method' => 'forbidden'
			]);
		}
	}

	private function currentUser(Session $session) {
		if(empty($session->user))
			return false;
		if( $session->ip != $_SERVER['REMOTE_ADDR'] )
			return false;

		return $this->service->getUser($session->user);
	}

	private function reloginUser(Session $session) {
		if( empty( $_COOKIE[self::COOKIE_PREFIX."_ID"] ))
			return false;
		if( empty( $_COOKIE[self::COOKIE_PREFIX."_KEY"] ))
			return false;

		$id = $_COOKIE[self::COOKIE_PREFIX."_ID"];
		$key = $_COOKIE[self::COOKIE_PREFIX."_KEY"];

		if( !$user = $this->service->relogin($id, $key))
			return $this->killCookies();

		return $user;
	}

	public function login($name, $pass, $relogin = false) {
		if(!$user = $this->service->login($name, $pass))
			return false;

		if( $relogin ) {
			$lifetime = time() + self::COOKIE_LIFETIME;
			setcookie( self::COOKIE_PREFIX."_ID", $user->id, $lifetime );
			setcookie( self::COOKIE_PREFIX."_KEY", $this->service->loginKey( $pass, $user->pass_salt, $user->pass_format ), $lifetime );
		}

		$session = $this->container->get('Session');
		$session->user = $user->id;
		$session->ip = $_SERVER['REMOTE_ADDR'];

		return $user;
	}

	public function logout() {
		$session = $this->container->get('Session');
		$session->user = 0;
		$this->killCookies();
	}

	private function killCookies() {
		$lifetime = time() - 1;
		setcookie(self::COOKIE_PREFIX . "_ID", 0, $lifetime);
		setcookie(self::COOKIE_PREFIX . "_KEY", 0, $lifetime);
		return false;
	}

	public function requires() {
		return [
				'Iv\Framework\Session\Session'
		];
	}
}