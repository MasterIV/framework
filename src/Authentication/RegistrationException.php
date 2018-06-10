<?php


namespace Iv\Framework\Authentication;


class RegistrationException extends \Exception {
	/** @var string[] */
	private $errors;

	/**
	 * RegistrationException constructor.
	 * @param string|string[] $errors
	 */
	public function __construct($errors) {
		$this->errors = is_array($errors) ? $errors : [$errors];
	}

	public function getErrors() {
		return $this->errors;
	}
}