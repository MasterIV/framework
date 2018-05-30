<?php


namespace Iv\Framework\Injection;


abstract class Container {
	private $services = [];

	public abstract function create($name);

	public function get($service) {
		if(empty($this->services[$service]))
			$this->services[$service] = $this->create($service);
		return $this->services[$service];
	}
}