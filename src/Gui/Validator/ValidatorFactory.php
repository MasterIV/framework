<?php


namespace Iv\Framework\Gui\Validator;


class ValidatorFactory {
	/** @var string */
	public static $validators = [
		'email' => 'Iv\Framework\Gui\Validator\EmailValidator',
		'number' => 'Iv\Framework\Gui\Validator\NumberValidator',
		'length' => 'Iv\Framework\Gui\Validator\LengthValidator',
		'required' => 'Iv\Framework\Gui\Validator\RequiredFieldValidator',
		'regex' => 'Iv\Framework\Gui\Validator\RegexValidator',
		'allow' => 'Iv\Framework\Gui\Validator\WhiteListValidator',
		'prevent' => 'Iv\Framework\Gui\Validator\BlackListValidator',
	];

	/**
	 * @param $validators
	 * @return FormValidator[]
	 * @throws \Exception
	 */
	public static function create($validators) {
		$result = [];
		if(is_string($validators))
			$validators = explode('|', $validators);

		foreach($validators as $validator) {
			if(is_string($validator)) {
				$args = explode(',', $validator);
				$type = array_shift($args);

				if(isset(self::$validators[$type])) {
					$class = new \ReflectionClass(self::$validators[$type]);
					$result[] = $class->newInstanceArgs($args);
				} else {
					throw new \Exception('Unknown validator: '.$type);
				}
			} elseif ($validator instanceof FormValidator) {
				$result[] = $validator;
			} else {
				throw new \Exception('Unknown validator: '.$validator);
			}
		}

		return $result;
	}
}