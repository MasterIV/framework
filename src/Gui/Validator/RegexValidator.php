<?php


namespace Iv\Framework\Gui\Validator;


class RegexValidator implements FormValidator {
	private $pattern;

	/**
	 * BlacklistValidator constructor.
	 * @param $pattern
	 */
	public function __construct($pattern) {
		$this->pattern = $pattern;
	}

	public function validate($value) {
		return !preg_match($this->pattern, $value);
	}

	public function message() {
		return "The input does not match the required format.";
	}
}