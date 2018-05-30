<?php


namespace Iv\Framework\Routing\Annotation;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Route {
	/** @var string */
	public $route;
}