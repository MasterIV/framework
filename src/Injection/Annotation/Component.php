<?php

namespace Iv\Framework\Injection\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Component
{
	/** @var string */
	public $name;
	/** @var string */
	public $collection;
}