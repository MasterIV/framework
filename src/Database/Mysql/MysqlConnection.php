<?php

namespace Iv\Framework\Database\Mysql;

use Iv\Framework\Database\Connection;
use Iv\Framework\Database\DbException;
use Iv\Framework\Database\Result;
use Iv\Framework\Database\Table;

class MysqlConnection extends \mysqli implements Connection {
	/**
	 * Closes the connection on object destruction
	 */
	public function __destruct() {
		$this->close();
	}

	/**
	 * Creates a teable object for the table that equals the property name
	 * @param string $name
	 * @return Table
	 */
	public function __get( $name ) {
		return $this->t( $name );
	}

	/**
	 * Creates Table Object
	 * @param string $name
	 * @return Table
	 */
	public function t( $name ) {
		return new MysqlTable( $this, $name );
	}

	/**
	 * Directly executes SQL
	 * @param string $query
	 * @return boolean
	 * @throws DbException
	 */
	public function exec( $query ) {
		if( $result = parent::query( $query )) return $result;
		else throw new DbException( $query, $this );
	}

	/**
	 * Formats an SQL
	 * @param array $args First element is the sql an the rest are arguments
	 * @return string
	 */
	public function formatSQL( $args ) {
		$sql = array_shift( $args );

		foreach( $args as &$arg )
			$arg = $this->escape($arg);

		return vsprintf( $sql, $args );
	}

	/**
	 * Executes a query and returns a result set
	 * Additional parameters may be given
	 * @param string $query
	 * @return Result
	 */
	public function query( $query ) {
		if(func_num_args() > 1 )
			$query = $this->formatSQL( func_get_args());

		if( $result = parent::query( $query )) return new MysqlResult($result);
		else throw new DbException( $query, $this );
	}

	/**
	 * Formats a Statement
	 * Additional parameters may be given
	 * @param string $sql
	 * @return string
	 */
	public function format( $sql ) {
		if( func_num_args() > 1 )
			$sql = $this->formatSQL( func_get_args());
		return $sql;
	}

	/**
	 * Returns the last inserted id
	 * @return int
	 */
	public function id() {
		return $this->insert_id;
	}

	/**
	 * Escapes a string
	 * @param string $value
	 * @return string
	 */
	public function escape( $value ) {
		return $this->escape_string( $value );
	}
}
