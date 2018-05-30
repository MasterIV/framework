<?php
namespace Iv\Framework\Database;

interface Result {
	/**
	 * Returns the number of rows found
	 * @return int
	 */
	public function num_rows();

	/**
	 * Fetches a record as associative array
	 * @return array
	 */
	public function assoc();

	/**
	 * Fetches a record as numeric array
	 * @return array
	 */
	public function row();

	/**
	 * Fetches a record as object
	 * @return \stdclass
	 */
	public function object();

	/**
	 * Fetches a singe value
	 * @param int $key column number
	 * @return mixed
	 */
	public function value($key = 0);

	/**
	 * Fetch all records in two dimensional accociative array
	 * @param string $key
	 * @return array
	 */
	public function assocs($key = NULL);

	/**
	 * Fetch all records in two dimensional numeric array
	 * @param int $key
	 * @return array
	 */
	public function rows($key = NULL);

	/**
	 * Fetch all records in array of objects
	 * @param string $key
	 * @return array
	 */
	public function objects($key = NULL);

	public function values($key = 0);

	public function relate($value = 'name', $key = 'id');

	public function current();

	public function key();

	public function next();

	public function rewind();

	public function valid();
}