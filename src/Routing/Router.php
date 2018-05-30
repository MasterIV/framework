<?php

namespace Iv\Framework\Routing;

class Router {
	const CHILDREN = 'c';
	const ROUTE = 'r';
	const MATCHER = 'm';

	private $routes;

	/**
	 * Dispatcher constructor.
	 * @param $routes
	 */
	public function __construct($routes = null) {
		$this->routes = $routes ?: $this->init();
	}

	private function init() {
		return [
				Router::CHILDREN => [],
				Router::MATCHER => [],
		];
	}

	public function add($path, $route) {
		$steps = explode('/', $path);
		$pointer =& $this->routes;

		while(count($steps)) {
			$step = array_shift($steps);
			if(empty($step)) continue;

			$type = $step[0] == '$'
					? Router::MATCHER
					: Router::CHILDREN;

			if(empty($pointer[$type][$step]))
				$pointer[$type][$step] = $this->init();
			$pointer =& $pointer[$type][$step];
		}

		$pointer[Router::ROUTE] = $route;
	}

	private function match($pointer, $steps) {
		while(count($steps)) {
			$step = array_shift($steps);
			if(empty($step)) continue;

			if(isset($pointer[self::CHILDREN][$step])) {
				$pointer = $pointer[self::CHILDREN][$step];
				continue;
			}

			foreach($pointer[self::MATCHER] as $param => $matcher)
				if($route = $this->match($matcher, $steps)) {
					$route[$param] = $step;
					$route['args'] = true;
					return $route;
				}

			return false;
		}

		if( empty( $pointer[self::ROUTE] )) {
			return false;
		} else {
			$route = $pointer[self::ROUTE];
			$route['args'] = false;
			return $route;
		}
	}

	public function route($path) {
		return $this->match($this->routes,  explode('/', $path));
	}

	public function dump() {
		return $this->routes;
	}
}