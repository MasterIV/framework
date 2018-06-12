<?php


namespace Iv\Framework\Gui\Validator;


class ArrayKeyValidator implements FormValidator {
	private $map;

	/**
	 * ArrayKeyValidator constructor.
	 * @param $map
	 */
	public function __construct($map) {
		$this->map = $map;
	}

	public function validate($value) {
		return isset($this->map[$value]);
	}

	public function message() {
		return "The provided value is not a selectable option.";
	}
}