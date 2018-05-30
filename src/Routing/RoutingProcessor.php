<?php


namespace Iv\Framework\Routing;


use Iv\Framework\Configuration\Processor;
use Iv\Framework\Routing\Annotation\Controller;
use Iv\Framework\Routing\Annotation\Route;

class RoutingProcessor implements Processor {
	/** @var Controller[] */
	private $controllers = [];

	/**
	 * @param \ReflectionClass $class
	 * @param $annotation
	 */
	public function handleClass($class, $annotation) {
		if($annotation instanceof Controller) {
			if(empty($annotation->name))
				$annotation->name = $class->getShortName();
			$this->controllers[$class->getName()] = $annotation;
		}
	}

	public function handleConstructor($class, $annotation) {}

	/**
	 * @param \ReflectionClass $class
	 * @param \ReflectionMethod $method
	 * @param $annotation
	 */
	public function handleMethod($class, $method, $annotation) {
		if(!$annotation instanceof Route) return;
		$this->controllers[$class->getName()]->add($annotation, $method);
	}

	private function getPath(Controller $controller, $route) {
		return $controller->route . '/' . $route['route'];
	}

	/** @return Router */
	public function router() {
		$router = new Router();

		foreach($this->controllers as $controller)
			foreach($controller->getChildren() as $route) {
				$path = $this->getPath($controller, $route);
				$router->add($path, $route);
			}

		return $router;
	}
}