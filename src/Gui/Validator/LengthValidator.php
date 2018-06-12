<?php


namespace Iv\Framework\Gui\Validator;


class LengthValidator implements FormValidator {
	private $min;
	private $max;

	/**
	 * LengthValidator constructor.
	 * @param $min
	 * @param $max
	 */
	public function __construct($min = 0, $max = null) {
		$this->min = $min;
		$this->max = $max;
	}

	public function validate($value) {
		$len = strlen($value);
		return $len >= $this->min && (!$this->max || $len <= $this->max);
	}

	public function message() {
		if($this->min && $this->max)
			return "The entered value needs to have between {$this->min} and {$this->max} characters.";
		elseif ($this->min)
			return "Please enter at least {$this->min} characters.";
		else
			return "The entered value can have a maximum of {$this->max} characters.";
	}
}