<?php


namespace Iv\Framework\Gui\Template;


class Link {
	public $label;
	public $color;
	public $url;

	/**
	 * Button constructor.
	 * @param string $label
	 * @param string $color
	 * @param string $url
	 */
	public function __construct($label, $url, $color = 'secondary') {
		$this->label = $label;
		$this->color = $color;
		$this->url = $url;
	}
}