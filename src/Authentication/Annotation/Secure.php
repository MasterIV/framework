<?php

namespace Iv\Framework\Authentication\Annotation;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Secure {
	/** @var string[] */
	public $permissions = [];
}