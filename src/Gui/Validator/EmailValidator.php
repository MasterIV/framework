<?php


namespace Iv\Framework\Gui\Validator;


class EmailValidator implements FormValidator {
	public function validate($value) {
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}

	public function message() {
		return "Please enter a valid e-mail address.";
	}
}