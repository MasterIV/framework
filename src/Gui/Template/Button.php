<?php


namespace Iv\Framework\Gui\Template;


class Button {
	public $label;
	public $color;
	public $type;

	/**
	 * Button constructor.
	 * @param string $label
	 * @param string $color
	 * @param string $type
	 */
	public function __construct($label, $color = 'default', $type = 'button') {
		$this->label = $label;
		$this->color = $color;
		$this->type = $type;
	}
}