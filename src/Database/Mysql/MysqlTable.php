<?php

namespace Iv\Framework\Database\Mysql;

use Iv\Framework\Database\Result;
use Iv\Framework\Database\Table;

class MysqlTable implements \IteratorAggregate, Table {
	private $db;
	private $name = '';

	public function  __construct(MysqlConnection $db, $name ) {
		$this->db = $db;
		$this->name = $name;
	}

	public function __toString() {
		return $this->name;
	}

	public function getIterator() {
		return $this->all();
	}

	/**
	 * Selects a record using a single column
	 * @param string $name
	 * @param array $arguments
	 * @return \stdClass
	 */
	public function __call( $name, $arguments ) {
		return $this->row( $arguments[0], $name )->object();
	}

	/**
	 * Returns a list of fields from a table
	 * @return array
	 */
	public function flist() {
		return $this->db->query( "SHOW COLUMNS FROM `{$this->name}`;" )->rows();
	}

	/**
	 * Returns a result containing all records from the table
	 * @return Result
	 */
	public function all() {
		return $this->get( 1 );
	}

	/**
	 * Creates a query and executes it based on the given conditions
	 * @param string $condition
	 * @return Result
	 */
	public function get( $condition ) {
		if(func_num_args() > 1 ) $condition = $this->db->formatSQL( func_get_args());
		return $this->db->query( "SELECT * FROM `{$this->name}` WHERE {$condition}" );
	}

	/**
	 * Selects a single record from a table using a single column primary key
	 * @param string $value
	 * @param string $column
	 * @return Result
	 */
	public function row( $value, $column = 'id') {
		return $this->get( "`$column` = '%s' LIMIT 1", $value );
	}

	/**
	 * Selects all record from a table using a single column primary key
	 * @param string $value
	 * @param string $column
	 * @return Result
	 */
	public function rows( $value, $column = 'id') {
		return $this->get( "`$column` = '%s'", $value );
	}

	/**
	 * Creates and executes a delete-statement
	 * @param string $condition
	 * @return boolean
	 */
	public function del( $condition ) {
		if(func_num_args() > 1 ) $condition = $this->db->formatSQL( func_get_args());
		return $this->db->exec( "DELETE FROM `{$this->name}` WHERE {$condition}" );
	}

	/**
	 * Deletes a record using a single column primary key
	 * @param string $value
	 * @param string $column
	 * @return boolean
	 */
	public function delRow( $value, $column = 'id') {
		return $this->del( "`$column` = '%s' LIMIT 1", $value );
	}

	/**
	 * Creates a new record in a table based on an array of values
	 * @param array $values column => value
	 * @param string $type INSERT or REPLACE
	 * @return boolean
	 */
	public function insert( $values, $type = 'INSERT' ) {
		$values['create_date'] = time();
		if( function_exists( 'current_user' ))
			$values['create_by'] = current_user();

		$fields = $vals = array();
		foreach( $this->flist() as $f )
			if( isset( $values[$f[0]] )) {
				$fields[] = "`$f[0]`";

				if(empty( $values[$f[0]] ) && $f[2] == 'YES') $vals[] =  "NULL";
				else $vals[] = "'".$this->db->escape( $values[$f[0]] )."'";
			}

		return $this->db->exec( "{$type} INTO `{$this->name}` ( ".implode( ", ", $fields ).") VALUES ( ".implode( ", ", $vals ).");" );
	}

	/**
	 * Updates a table based on an array
	 * @param array $values column => value
	 * @param string $condition
	 * @return boolean
	 */
	public function update( $values, $condition ) {
		if(func_num_args() > 2 ) {
			$args = func_get_args();
			array_shift( $args );
			$condition = $this->db->formatSQL( $args );
		}

		$values['update_date'] = time();
		if( function_exists( 'current_user' ))
			$values['update_by'] = current_user();

		$ups = array();
		foreach( $this->flist() as $f )
			if( isset( $values[$f[0]] ))
				if( empty( $values[$f[0]] ) && $f[2] == 'YES' ) $ups[] = "`$f[0]` = NULL";
				else $ups[] = "`$f[0]` = '".$this->db->escape( $values[$f[0]] )."'";

		return $this->db->exec( "UPDATE `{$this->name}` SET ".implode( ", ", $ups )." WHERE {$condition}" );
	}

	/**
	 * Creates multiple records in a table based on an array of values
	 * @param array $values column => value
	 * @param string $type INSERT or REPLACE
	 * @return boolean
	 */
	public function multiInsert( $values, $type = 'INSERT' ) {
		if( empty( $values )) return null;

		$flist = $vals = array();
		foreach( $this->flist() as $f ) $flist[] = $f[0];

		$possible = array_merge( array_keys( $values[0] ), array( 'create_date', 'create_by' ));
		$fields = array_intersect( $possible, $flist );

		foreach( $values as $row ) {
			$val = array();
			$row['create_date'] = time();
			if( function_exists( 'current_user' ))
				$row['create_by'] = current_user();

			foreach( $fields as $f )
				$val[] = "'".$this->db->escape( $row[$f] )."'";

			$vals[] = '('.implode(',', $val ).')';
		}

		return $this->db->exec( "{$type} INTO `{$this->name}` ( `".implode( "`, `", $fields )."` ) VALUES ".implode( ", ", $vals ).";" );
	}


	public function multiUpdate( $values ) {
		throw new \Exception('to be implemented');
	}

	/**
	 * Updates a record in a table based on an array of values using a single column primary key
	 * @param array $values column => value
	 * @param string $value
	 * @param string $column
	 * @return boolean
	 */
	public function updateRow( $values, $value, $column = 'id' ) {
		return $this->update( $values, "`$column` = '%s'", $value );
	}
}
