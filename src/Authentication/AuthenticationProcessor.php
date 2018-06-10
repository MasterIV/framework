<?php


namespace Iv\Framework\Authentication;


use Iv\Framework\Authentication\Annotation\Accessible;
use Iv\Framework\Authentication\Annotation\Secure;
use Iv\Framework\Configuration\Processor;

class AuthenticationProcessor implements Processor {
	private $configuration = [];

	/**
	 * @param \ReflectionClass $class
	 * @param $annotation
	 */
	public function handleClass($class, $annotation) {
		if($conf = $this->convertAnnotation($annotation)){
			$name = $class->getName();
			$this->configuration[$name] = $conf;
			$this->configuration[$name]['methods'] = [];
		}
	}

	/**
	 * @param \ReflectionClass $class
	 * @param \ReflectionMethod $method
	 * @param $annotation
	 */
	public function handleMethod($class, $method, $annotation) {
		if($conf = $this->convertAnnotation($annotation)){
			$name = $class->getName();
			if(empty( $this->configuration[$name]))
				$this->configuration[$name] = ['methods' => []];

			$this->configuration[$name]['methods'][$method->getName()] = $conf;
		}
	}

	private function convertAnnotation($annotation) {
		if ($annotation instanceof Secure) {
			return [
					'public' => false,
					'requires' => $annotation->permissions
			];
		}

		if ($annotation instanceof Accessible) {
			return [ 'public' => true ];
		}

		return false;
	}

	public function dump() {
		return $this->configuration;
	}

	public function handleConstructor($class, $annotation) {
	}
}