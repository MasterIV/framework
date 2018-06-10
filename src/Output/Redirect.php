<?php


namespace Iv\Framework\Output;

use Iv\Framework\Routing\Response;

class Redirect implements Response {
	/** @var string */
	private $location;

	/**
	 * Redirect constructor.
	 * @param string $location
	 */
	public function __construct($location) {
		$this->location = defined('HTTP_ROOT') && $location[0] == '/' ? HTTP_ROOT.$location : $location;
	}

	public function send() {
		header("LOCATION: {$this->location}");
	}
}