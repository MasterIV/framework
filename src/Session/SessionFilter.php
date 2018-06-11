<?php


namespace Iv\Framework\Session;


use Iv\Framework\Injection\Annotation\Component;
use Iv\Framework\Injection\Container;
use Iv\Framework\Routing\Filter;
use Iv\Framework\Routing\FilterChain;

/** @Component() */
class SessionFilter implements Filter {
	/** @var Container */
	private $container;

	/**
	 * SessionFilter constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container) {
		$this->container = $container;
	}

	public function apply($route, FilterChain $chain) {
		$this->container->set("Session", new Session());
		return $chain->next($route);
	}

	public function requires() {
		return [];
	}
}