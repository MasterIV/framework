<?php
namespace Iv\Framework\Database;

use Iv\Framework\Database\Mysql\MysqlResult;

interface Table {
	public function getIterator();

	/**
	 * Returns a list of fields from a table
	 * @return array
	 */
	public function flist();

	/**
	 * Returns a result containing all records from the table
	 * @return MysqlResult
	 */
	public function all();

	/**
	 * Creates a query and executes it based on the given conditions
	 * @param string $condition
	 * @return MysqlResult
	 */
	public function get($condition);

	/**
	 * Selects a single record from a table using a single column primary key
	 * @param string $value
	 * @param string $column
	 * @return MysqlResult
	 */
	public function row($value, $column = 'id');

	/**
	 * Selects all record from a table using a single column primary key
	 * @param string $value
	 * @param string $column
	 * @return MysqlResult
	 */
	public function rows($value, $column = 'id');

	/**
	 * Creates and executes a delete-statement
	 * @param string $condition
	 * @return boolean
	 */
	public function del($condition);

	/**
	 * Deletes a record using a single column primary key
	 * @param string $value
	 * @param string $column
	 * @return boolean
	 */
	public function delRow($value, $column = 'id');

	/**
	 * Creates a new record in a table based on an array of values
	 * @param array $values column => value
	 * @param string $type INSERT or REPLACE
	 * @return boolean
	 */
	public function insert($values, $type = 'INSERT');

	/**
	 * Updates a table based on an array
	 * @param array $values column => value
	 * @param string $condition
	 * @return boolean
	 */
	public function update($values, $condition);

	/**
	 * Creates multiple records in a table based on an array of values
	 * @param array $values column => value
	 * @param string $type INSERT or REPLACE
	 * @return boolean
	 */
	public function multiInsert($values, $type = 'INSERT');

	public function multiUpdate($values);

	/**
	 * Updates a record in a table based on an array of values using a single column primary key
	 * @param array $values column => value
	 * @param string $value
	 * @param string $column
	 * @return boolean
	 */
	public function updateRow($values, $value, $column = 'id');
}