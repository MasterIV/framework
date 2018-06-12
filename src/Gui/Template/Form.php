<?php


namespace Iv\Framework\Gui\Template;


use Iv\Framework\Files;

class Form {
	/** @var string */
	public $id;
	/** @var string */
	public $action;
	/** @var string */
	public $method;

	/** @var Field[] */
	public $fields = [];
	/** @var Button[] */
	public $buttons = [];
	/** @var Link[] */
	public $links = [];

	/**
	 * Form constructor.
	 * @param string $id
	 * @param string $action
	 * @param string $submit
	 * @param string $method
	 */
	public function __construct($id, $action, $submit = 'Save', $method = 'post') {
		$this->id = $id;
		$this->action = $action;
		$this->method = $method;

		$this->buttons = [
				new Button($submit, 'primary', 'submit')
		];
	}

	public function text($name, $label, $placeholder = null) {
		return $this->fields[] = new Field($name, $label, 'text', $placeholder);
	}

	public function password($name, $label, $placeholder = null) {
		return $this->fields[] = new Field($name, $label, 'password', $placeholder);
	}

	public function email($name, $label, $placeholder = null) {
		return $this->fields[] = new Field($name, $label, 'email', $placeholder);
	}

	public function textarea($name, $label, $placeholder = null) {
		return $this->fields[] = new Field($name, $label, 'textarea', $placeholder);
	}

	public function checkbox($name, $label) {
		return $this->fields[] = new Field($name, $label, 'checkbox');
	}

	public function select($name, $label, $options) {
		$select = $this->fields[] = new Field($name, $label, 'select');
		$select->options = $options;
		return $select;
	}

	public function fill($data) {
		foreach ($this->fields as $field)
			$field->fill($data);
	}

	public function button($label) {
		$this->buttons[] = new Button($label);
	}

	public function link($label, $url) {
		$this->links[] = new Link($label, $url);
	}

	public function validate($data) {
		if(empty($data))
			return false;

		$valid = true;

		foreach ($this->fields as $field)
			$valid = $field->validate($data) && $valid;

		return $valid;
	}

	public function __toString() {
		return Files::templates('form')->render((array)$this);
	}
}