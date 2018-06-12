<?php


namespace Iv\Framework\Gui\Validator;

use Iv\Framework\Database\Table;

class ExistenceValidator implements FormValidator {
	/** @var Table */
	private $table;
	/** @var string */
	private $column;

	/**
	 * ExistenceValidator constructor.
	 * @param Table $table
	 * @param string $column
	 */
	public function __construct(Table $table, $column = 'name') {
		$this->table = $table;
		$this->column = $column;
	}

	public function validate($value) {
		return !$this->table->row($value, $this->column)->num_rows();
	}

	public function message() {
		return "This value is already in use.";
	}
}