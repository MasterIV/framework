<?php


namespace Iv\Framework\Injection;


class NamespaceContainer extends Container {
	/** @var string */
	private $namespace;

	/**
	 * SimpleContainer constructor.
	 * @param string $namespace
	 */
	public function __construct($namespace) {
		$this->namespace = $namespace . '\\';
	}

	public function create($name) {
		$class = $this->namespace . $name;
		return new $class();
	}

	public function has($name) {
		return class_exists($this->namespace . $name);
	}
}