<?php


namespace Iv\Framework\Gui\Validator;


class RequiredFieldValidator implements FormValidator {
	public function validate($value) {
		return !empty($value);
	}

	public function message() {
		return "Please fill in this field.";
	}
}