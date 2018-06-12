<?php


namespace Iv\Framework\Gui\Validator;


class WhiteListValidator implements FormValidator {
	private $pattern;

	/**
	 * BlacklistValidator constructor.
	 * @param $pattern
	 */
	public function __construct($pattern) {
		$this->pattern = $pattern;
	}

	public function validate($value) {
		return !preg_match("/[{$this->pattern}]+/is", $value);
	}

	public function message() {
		return "The input contained invalid characters.";
	}
}