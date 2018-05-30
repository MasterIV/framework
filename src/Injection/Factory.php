<?php


namespace Iv\Framework\Injection;


class Factory {
	public $name;
	public $factory;
	public $method;

	/**
	 * Factory constructor.
	 * @param $name
	 * @param $factory
	 * @param $method
	 */
	public function __construct($name, $factory, $method) {
		$this->name = $name;
		$this->factory = $factory;
		$this->method = $method;
	}


}