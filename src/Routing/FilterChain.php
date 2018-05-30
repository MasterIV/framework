<?php


namespace Iv\Framework\Routing;


class FilterChain {
	/** @var Filter[] */
	private $filters;
	/** @var Dispatcher */
	private $dispatcher;
	/** @var int */
	private $index = 0;

	/**
	 * FilterChain constructor.
	 * @param Filter[] $filters
	 * @param Dispatcher $dispatcher
	 */
	public function __construct($filters, $dispatcher) {
		$this->filters = $filters;
		$this->dispatcher = $dispatcher;
	}

	public function next($route) {
		if($this->index < count( $this->filters))
			return $this->filters[++$this->index-1]->apply($route, $this);
		else
			return $this->dispatcher->process($route);
	}
}