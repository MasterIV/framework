<?php

namespace Iv\Framework\Injection\Annotation;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Inject {
	/** @var array */
	public $dependencies;
}
