<?php


namespace Iv\Framework\Gui\Validator;


class NumberValidator implements FormValidator {
	private $max;
	private $min;

	/**
	 * NumberValidator constructor.
	 * @param $max
	 * @param $min
	 */
	public function __construct($max = PHP_INT_MAX, $min = PHP_INT_MIN) {
		$this->max = $max;
		$this->min = $min;
	}

	public function validate($value) {
		$int = intval($value);
		return $value == $int && $int <= $this->max && $int >= $this->min;
	}

	public function message() {
		return "Please enter a valid number between {$this->min} and {$this->max}.";
	}
}