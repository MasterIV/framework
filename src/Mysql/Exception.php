<?php

namespace Iv\Framework\Mysql;

/**
 * This exception is thorwn when an SQL-Error occurs
 * Contains the query and the error message
 */
class Exception extends \Exception {
	protected $query;
	protected $error;

	function  __construct( $sql, Connection $db ) {
		parent::__construct( sprintf("SQL Error: %s\nIn the Query: %s", $db->error, $sql), $db->errno );
		$this->error = $db->error;
		$this->query = $sql;
	}

	/**
	 * returns the sql that caused the error
	 * @return string Query
	 */
	public function getSql() {
		return $this->query;
	}

	/**
	 * returns the sql error message
	 * @return string MySQL error message
	 */
	public function getError() {
		return $this->error;
	}
}
