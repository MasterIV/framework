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


}