<?php

namespace Iv\Framework\Database\Mysql;

use Iv\Framework\Database\Result;

class MysqlResult implements \Iterator, Result {
	/** @var \mysqli_result  */
	protected $res;

	protected $row = 0;
	protected $max = 0;

	public function  __construct( \mysqli_result $res ) {
		$this->res = $res;
		$this->max = $this->num();
	}

	/**
	 * Returns the number of rows found
	 * @return int
	 */
	public function num() {
		return $this->res->num_rows;
	}

	/** Alias for num() */
	public function num_rows(){
		return $this->num();
	}

	/**
	 * Fetches a record as associative array
	 * @return array
	 */
	public function assoc() {
		return $this->res->fetch_assoc();
	}

	/**
	 * Fetches a record as numeric array
	 * @return array
	 */
	public function row() {
		return $this->res->fetch_row();
	}

	/**
	 * Fetches a record as object
	 * @return \stdClass
	 */
	public function object() {
		return $this->res->fetch_object();
	}

	/**
	 * Fetches a singe value
	 * @param int $key column number
	 * @return mixed
	 */
	public function value( $key = 0 ) {
		$erg = $this->row();
		return $erg[$key];
	}

	/**
	 * Fetch all records in two dimensional accociative array
	 * @param string $key
	 * @return array
	 */
	public function assocs( $key = NULL ) {
		$erg = array();

		if( $key ) while( $row = $this->assoc()) $erg[$row[$key]] = $row;
		else while( $row = $this->assoc()) $erg[] = $row;

		return $erg;
	}


	/**
	 * Fetch all records in two dimensional numeric array
	 * @param int $key
	 * @return array
	 */
	public function rows( $key = NULL ) {
		$erg = array();

		if( $key ) while( $row = $this->row()) $erg[$row[$key]] = $row;
		else while( $row = $this->row()) $erg[] = $row;

		return $erg;
	}


	/**
	 * Fetch all records in array of objects
	 * @param string $key
	 * @return array
	 */
	public function objects( $key = NULL ) {
		$erg = array();

		if( $key ) while( $row = $this->object()) $erg[$row->{$key}] = $row;
		else while( $row = $this->object()) $erg[] = $row;

		return $erg;
	}

	public function values( $key = 0 ) {
		$erg = array();

		while( $row = $this->row())
			$erg[] = $row[$key];

		return $erg;
	}

	public function relate( $value = 'name', $key = 'id' ) {
		$erg = array();

		while( $row = $this->assoc())
			$erg[$row[$key]] = $row[$value];

		return $erg;
	}

	public function current() {
		return $this->assoc();
	}

	public function key() {
		return $this->row;
	}

	public function next() {
		$this->row++;
	}

	public function rewind() {
		$this->row = 0;
		$this->res->data_seek( 0 );
	}

	public function valid() {
		return $this->row < $this->max;
	}
}
