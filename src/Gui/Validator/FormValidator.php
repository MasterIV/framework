<?php


namespace Iv\Framework\Gui\Validator;


interface FormValidator {
	public function validate($value);
	public function message();
}