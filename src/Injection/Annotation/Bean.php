<?php


namespace Iv\Framework\Injection\Annotation;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Bean extends Inject {
	/** @var string */
	public $name;
}