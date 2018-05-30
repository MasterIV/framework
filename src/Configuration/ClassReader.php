<?php


namespace Iv\Framework\Configuration;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class ClassReader {
	/** @var AnnotationReader */
	private $reader;
	/** @var Processor[] */
	private $processors = [];

	public function __construct($processors = []) {
		AnnotationRegistry::registerLoader('class_exists');
		$this->reader = new AnnotationReader();
		$this->processors = $processors;
	}

	/**
	 * Adds Inject and Service Annotation to global Import,
	 * So you don't have to put a use statement in every File you want to use them
	 * A dirty Hack: Writing a private property through reflection because
	 * Doctrine does normally not allow this...
	 * @param $dir
	 * @param $namespace
	 */
	public function enableGlobalImports($dir, $namespace) {
		$readerRef = new \ReflectionClass('Doctrine\Common\Annotations\AnnotationReader');
		$property = $readerRef->getProperty('globalImports');
		$property->setAccessible(true);

		$classes = [];
		foreach ($this->findClasses($dir, $namespace) as $name) {
			$key = strtolower(substr($name, strrpos($name, '\\') + 1));
			$classes[$key] = substr($name, 1);
			class_exists($name);
		}

		$property->setValue(array_merge($property->getValue(), $classes));
	}

	/**
	 * Find classes in the given path
	 * The path should be inside the src folder
	 * @param $dir
	 * @param $namespace
	 * @return \string[] array
	 */
	private function findClasses($dir, $namespace) {
		$objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
		$classes = [];

		foreach ($objects as $name => $object)
			if ($object->isFile() && $object->getExtension() == 'php')
				$classes[] = str_replace([$dir, '/', '.php'], [$namespace, '\\', ''], $object->getPathname());

		return $classes;
	}

	/**
	 * Read Dependency Injection Annotations
	 * @param $dir string Sourcecode Directory
	 * @param $namespace
	 */
	public function read($dir, $namespace) {
		foreach ($this->findClasses($dir, $namespace) as $name) {
			$class = new \ReflectionClass($name);

			$classAnnotation = $this->reader->getClassAnnotations($class);
			$this->process($class, $classAnnotation);
		}
	}

	/**
	 * @param \ReflectionClass $class
	 * @param $classAnnotation
	 */
	private function process($class, $classAnnotation) {
		foreach ($classAnnotation as $annotation)
			foreach ($this->processors as $p)
				$p->handleClass($class, $annotation);

		foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
			$methodName = $method->getName();
			if ($methodAnnotations = $this->reader->getMethodAnnotations($method)) {
				foreach ($methodAnnotations as $annotation)
					if ($methodName == '__construct')
						foreach ($this->processors as $p)
							$p->handleConstructor($class, $annotation);
					else
						foreach ($this->processors as $p)
							$p->handleMethod($class, $method, $annotation);
			}
		}
	}
}