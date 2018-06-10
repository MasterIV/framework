<?php


namespace Iv\Framework\Authentication;


use Iv\Framework\Database\Connection;

class AuthenticationService {
	/** @var Connection */
	private $db;

	/**
	 * AuthenticationService constructor.
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		$this->db = $db;
	}

	public function login($name, $pass, $log = true) {
		if (empty($name) || empty($pass))
			return false;
		if (!$user = $this->db->user_data->name($name))
			return false;
		if (self::crypt($pass, $user->pass_salt, $user->pass_format) != $user->pass_hash)
			return false;

		$this->updateAccessInformation($user, $log);

		return $user;
	}

	public function relogin($id, $key) {
		if (!$user = $this->db->user_data->id($id))
			return false;
		if (self::transform($key, $user->pass_salt, $user->pass_format) != $user->pass_hash)
			return false;

		$this->updateAccessInformation($user, true);

		return $user;
	}

	public function getUser($id) {
		if (empty($id))
			return false;
		if (!$user = $this->db->user_data->id($id))
			return false;

		$this->updateAccessInformation($user);

		return $user;
	}

	public function register($name, $pass, $repeat, $email, $type = 1) {
		$errors = [];
		$minlen = 8;

		// Name Validation
		if (empty($name))
			$errors[] = 'Please enter a user name.';
		if (preg_match('/[^\w\d]/', $name, $m))
			$errors[] = 'The user name contains invalid characters: ' . htmlspecialchars($m[0]);

		// Email Validation
		if (empty($email))
			$errors[] = 'Please provide an E-Mail.';
		if (!$mail = filter_var($email, FILTER_VALIDATE_EMAIL))
			$errors[] = 'Provided E-Mail is invalid.';

		// Password Validation
		if (empty($pass))
			$errors[] = 'Please choose a password.';
		if ($minlen && strlen($pass) < $minlen)
			$errors[] = 'The password has to be at least ' . $minlen . ' characters long.';
		if ($pass != $repeat)
			$errors[] = 'Password and repetition do not match.';

		// Check blacklists
		if ($this->db->query("SELECT 1 FROM `user_blocked`
			WHERE '%s' LIKE CONCAT('%%', `pattern`,'%%')
			AND `type` = 'name'", $name)->num_rows())
			$errors[] = 'This user name is not allowed on this page.';
		if ($this->db->query("SELECT 1 FROM `user_blocked`
			WHERE '%s' LIKE CONCAT('%%', `pattern`,'%%')
			AND `type` = 'email'", $mail)->num_rows())
			$errors[] = 'This E-Mail is not allowed on this page.';

		// Already in use ?
		if ($this->db->id_get('user_data', $mail, 'email'))
			$errors[] = 'Die angegebene E-Mail ist bereits vergeben';
		if ($this->db->id_get('user_data', $name, 'name'))
			$errors[] = 'Der angegebene Name ist bereits vergeben';

		if (count($errors))
			return $errors;

		$this->db->user_data->insert([
				'name' => $name,
				'email' => $mail,
				'pass_salt' => $salt = uniqid(),
				'pass_hash' => self::crypt($pass, $salt),
				'type' => $type
		]);

		return $this->db->id();
	}

	/**
	 * creates a key that can be used for relogin
	 * @param string $string
	 * @param string $salt
	 * @param int $type
	 * @return string
	 */
	public function loginKey($string, $salt, $type = 0) {
		switch ($type) {
			case 1:
				return sha1($salt . sha1($string));
			default:
				return md5($string . $salt);
		}
	}

	/**
	 * transform a relogin key into the password hash
	 * @param string $string
	 * @param string $salt
	 * @param int $type
	 * @return string
	 */
	public static function transform($string, $salt, $type = 0) {
		switch ($type) {
			case 1:
				return sha1($salt . $string);
			default:
				return md5($salt . $string);
		}
	}

	/**
	 * Creates a relogin key and transforms it into the password hash
	 * @param string $string
	 * @param string $salt
	 * @param int $type
	 * @return string
	 */
	public static function crypt($string, $salt, $type = 0) {
		return self::transform(self::loginKey($string, $salt, $type), $salt, $type);
	}

	/**
	 * Set date and ip of last request (and login)
	 * @param $user
	 * @param bool $login
	 */
	private function updateAccessInformation($user, $login = false) {
		$info = [
				'last_refresh' => time(),
				'last_ip' => $_SERVER['REMOTE_ADDR']
		];

		if ($login)
			$info['last_login'] = time();

		$this->db->user_data->updateRow($info, $user->id);
	}
}