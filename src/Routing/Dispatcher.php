<?php


namespace Iv\Framework\Routing;

use Iv\Framework\Injection\Annotation\Component;
use Iv\Framework\Injection\Annotation\Inject;
use Iv\Framework\Injection\Container;

/** @Component() */
class Dispatcher {
	/** @var Container */
	private $container;
	/** @var Filter[] */
	private $filters;

	/**
	 * Dispatcher constructor.
	 * @param Container $container
	 * @param Filter[] $filters
	 * @Inject({"@Container", "@Filters"})
	 */
	public function __construct(Container $container, $filters = []) {
		$this->container = $container;
		$this->filters = $filters;

		// Sort filters to ensure correct order of execution
		usort($this->filters, function(Filter $a, Filter $b) {
			if(isset($a->requires()[get_class($b)]))
				return 1;
			if(isset($b->requires()[get_class($a)]))
				return -1;
			return count($a->requires()) - count($b->requires());
		});
	}

	/**
	 * Handles the given path
	 * @param array $route
	 */
	public function route($route) {
		if(empty($route))
			$route = [
					'controller' => 'ErrorController',
					'method' => 'notFound',
			];

		try {
			$filters = new FilterChain($this->filters, $this);
			$this->render($filters->next($route));
		} catch (\Exception $e) {
			$this->render($this->process([
					'controller' => 'ErrorController',
					'method' => $e instanceof UserException ? 'userError' : 'serverError',
					'$exception' => $e
			]));
		}
	}

	private function render($result) {
		if(is_object($result) && $result instanceof Response) {
			$result->send();
		} else {
			echo json_encode($result);
		}
	}

	/**
	 * @param array $route
	 * @return mixed
	 */
	public function process($route) {
		$controller = $this->container->get($route['controller']);
		$method = new \ReflectionMethod($controller, $route['method']);
		$args = [];

		foreach ($method->getParameters() as $p)
			if( isset( $route['$' . $p->getName()]))
				$args[] = $route['$' . $p->getName()];
			else if( isset($_REQUEST[$p->getName()]))
				$args[] = $_REQUEST[$p->getName()];
			else
				$args[] = null;

		return $method->invokeArgs($controller, $args);
	}
}