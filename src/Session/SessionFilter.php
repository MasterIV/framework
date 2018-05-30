<?php


namespace Iv\Framework\Session;


use Iv\Framework\Injection\Container;
use Iv\Framework\Routing\Filter;
use Iv\Framework\Routing\FilterChain;

class SessionFilter implements Filter {
	/** @var Container */
	private $container;

	public function apply($route, FilterChain $chain) {
		$this->container->set("Session", new Session());
		return $chain->next($route);
	}
}