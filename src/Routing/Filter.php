<?php


namespace Iv\Framework\Routing;

interface Filter {
	public function apply($route, FilterChain $chain);
	public function requires();
}

