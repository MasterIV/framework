<?php

namespace Iv\Framework\Injection;

use Iv\Framework\Configuration\Processor;
use Iv\Framework\Files;
use Iv\Framework\Injection\Annotation\Bean;
use Iv\Framework\Injection\Annotation\Component;
use Iv\Framework\Injection\Annotation\Inject;


/**
 * Handles the Component and Injection Annotations
 * and creates the service container from them.
 * @package Iv\System\Configuration\Processor
 */
class InjectionProcessor implements Processor  {
	/** @var Definition[] */
	private $definitions = [];
	/** @var Collection[] */
	private $collections = [];
	/** @var array */
	private $beans = [];

	/**
	 * InjectionProcessor constructor.
	 */
	public function __construct() {
		$this->definitions = [];
	}

	/**
	 * @param \ReflectionClass $class
	 * @param $annotation
	 */
	public function handleClass($class, $annotation) {
		if(!$annotation instanceof Component) return;

		$name = $annotation->name ?: $class->getShortName();
		$definition = new Definition($name, $class->getName());
		$this->definitions[$class->getName()] = $definition;

		if(empty($annotation->collection)) return;
		if(empty($this->collections[$annotation->collection]))
			$this->collections[$annotation->collection] = new Collection($annotation->collection);
		$this->collections[$annotation->collection]->add( $definition );
	}

	/**
	 * Create a list of Parameter Objects from dependency strings
	 * The type of injection depends on the first character:
	 *  '@' => inject service or collection
	 *  '#' => inject a parameter
	 *  '~' => inject cache file
	 * @param string[] $dependencies
	 * @return Parameter[]
	 * @throws \Exception
	 */
	public static function readDependencies($dependencies) {
		$params = [];

		foreach($dependencies as $d)
			if($d[0] == '@')
				$params[] = new Parameter('service', substr($d, 1));
			elseif($d[0] == '#')
				$params[] = new Parameter('parameter', substr($d, 1));
			elseif($d[0] == '~')
				$params[] = new Parameter('cache', substr($d, 1));
			else
				throw new \Exception('Type of Parameter unknown: '.$d);

		return $params;
	}

	/**
	 * Parses Inject annotations on the constructor.
	 * @param \ReflectionClass $class
	 * @param $annotation
	 */
	public function handleConstructor($class, $annotation) {
		if(!$annotation instanceof Inject) return;

		$this->definitions[$class->getName()]->constructor
			= $this->readDependencies($annotation->dependencies);
	}

	/**
	 * Parses Inject annotations on methods.
	 * @param \ReflectionClass $class
	 * @param \ReflectionMethod $method
	 * @param $annotation
	 */
	public function handleMethod($class, $method, $annotation) {
		if(!$annotation instanceof Inject)
			return;

		if($annotation instanceof Bean) {
			$name = $annotation->name ?: $method->getName();
			$this->beans[$name] = new Factory(
					$name, $this->definitions[$class->getName()]->name,
					new Method($method->getName(), $this->readDependencies($annotation->dependencies)));
		} else {
			$this->definitions[$class->getName()]->methods[] = new Method(
					$method->getName(),
					$this->readDependencies($annotation->dependencies));
		}

	}

	/**
	 * Create the container.php inside the cache directory
	 * @param $file
	 * @param string $name
	 */
	public function dump($file, $name = 'IvServiceContainer') {
		$template = Files::templates('container', 'parameter');
		file_put_contents($file, $template->render([
			'name' => $name,
			'definitions' => $this->definitions,
			'collections' => $this->collections,
			'factories' => $this->beans,
		]));
	}
}