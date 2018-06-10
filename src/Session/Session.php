<?php


namespace Iv\Framework\Session;


class Session {
	protected $name;
	protected $cookie_lifetime = 604800;

	/**
	 * Constructor starts a Session
	 * @param string $name Session Name
	 */
	public function __construct( $name = 'IVSESSID' ) {
		session_name( $this->name = $name );
		session_start();
	}

	/**
	 * Reads a session variable
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		return $_SESSION[$name];
	}

	/**
	 * Set as session variable
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		$_SESSION[$name] = $value;
	}
}