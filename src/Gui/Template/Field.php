<?php


namespace Iv\Framework\Gui\Template;


use Iv\Framework\Gui\Validator\ArrayKeyValidator;
use Iv\Framework\Gui\Validator\FormValidator;
use Iv\Framework\Gui\Validator\ValidatorFactory;

class Field {
	/** @var string */
	public $name;
	/** @var string */
	public $type;
	/** @var string */
	public $label;
	/** @var string */
	public $placeholder;
	/** @var mixed */
	public $default = null;
	/** @var mixed */
	public $value;
	/** @var string */
	public $error;
	/** @var array */
	public $options = [];
	/** @var FormValidator[] */
	private $validators = [];

	/**
	 * Field constructor.
	 * @param string $name
	 * @param string $label
	 * @param string $type
	 * @param null $placeholder
	 */
	public function __construct($name, $label, $type = 'text', $placeholder = null) {
		$this->name = $name;
		$this->type = $type;
		$this->label = $label;
		$this->placeholder = $placeholder;
	}

	public function standard($value) {
		$this->default = $value;
		return $this;
	}

	public function validators($validators) {
		$this->validators = ValidatorFactory::create($validators);
	}

	public function fill($data) {
		$this->value = empty($data[$this->name]) ? $this->default : $data[$this->name];
	}

	public function validate($data) {
		$value = empty($data[$this->name]) ? null : $data[$this->name];


		if($this->type == 'select')
			$this->validators[] = new ArrayKeyValidator($this->options);

		foreach ($this->validators as $validator)
			if(!$validator->validate($value)) {
				$this->error = $validator->message();
				return false;
			}

		return true;
	}
}