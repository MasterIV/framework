<?php


namespace Iv\Framework\Configuration;


interface Processor {
	public function handleClass($class, $annotation);
	public function handleConstructor($class, $annotation);
	public function handleMethod($class, $method, $annotation);
}