<?php


namespace Iv\Framework\Injection;

class InjectionContainer extends Container  {
	private $methods = [];
	private $parameters = [];

	/**
	 * Container constructor.
	 * @param array $methods
	 * @param $parameters
	 */
	public function __construct($methods, $parameters) {
		$this->methods = $methods;
		$this->parameters = $parameters;
		$this->set('Container', $this);
	}

	public function create($service) {
		if(empty($this->methods[$service]))
			throw new \Exception('invalid service: '.$service);
		return call_user_func([$this, $this->methods[$service]]);
	}

	public function setParameter($key, $value) {
		$this->parameters[$key] = $value;
	}

	public function getParameter($key) {
		return $this->parameters[$key];
	}

	protected function loadCache( $file ) {
		$fileName = 'cache/'.$file.'.php';
		return is_file($fileName) ? include $fileName : null;
	}

	public function has($name) {
		return !empty($this->services[$name]) || !empty($this->methods[$name]);
	}
}