<?php
namespace Iv\Framework\Database;

use Iv\Framework\Database\Mysql\MysqlResult;
use Iv\Framework\Database\Mysql\MysqlTable;

interface Connection {
	/**
	 * Creates a teable object for the table that equals the property name
	 * @param string $name
	 * @return Table
	 */
	public function __get( $name );

	/**
	 * Creates Table Object
	 * @param string $name
	 * @return Table
	 */
	public function t($name);

	/**
	 * Directly executes SQL
	 * @param string $query
	 * @return boolean
	 * @throws DbException
	 */
	public function exec($query);

	/**
	 * Formats an SQL
	 * @param array $args First element is the sql an the rest are arguments
	 * @return string
	 */
	public function formatSQL($args);

	/**
	 * Executes a query and returns a result set
	 * Additional parameters may be given
	 * @param string $query
	 * @return Result
	 */
	public function query($query);

	/**
	 * Formats a Statement
	 * Additional parameters may be given
	 * @param string $sql
	 * @return string
	 */
	public function format($sql);

	/**
	 * Returns the last inserted id
	 * @return int
	 */
	public function id();

	/**
	 * Escapes a string
	 * @param string $value
	 * @return string
	 */
	public function escape($value);
}