<?php

namespace Iv\Framework\Database;

use Iv\Framework\Files;
use Iv\Framework\Strings;

class Updater {
	/** @var Connection */
	private $db;
	/** @var string */
	private $dir;

	/**
	 * Migration constructor.
	 * @param Connection $db
	 */
	public function __construct(Connection $db, $dir = 'migration') {
		$this->db = $db;
		$this->dir = $dir;
	}

	public function create($name) {
		$file = preg_replace('/\W+/is', '_', $name);
		$class = Strings::camelize($file) . 'Migration';
		$fileName = $this->dir . DIRECTORY_SEPARATOR . date('Ymd-Hi-') . $file . '.php';
		file_put_contents($fileName, str_replace('MigrationClassName', $class, Files::asset('tpl/migration.php')));
		chmod($fileName, 0777);
	}

	public function init() {
		$this->system('init_migrations')->apply(true);
	}

	/**
	 * @param $name
	 * @return Migration
	 */
	public function system($name) {
		require Files::ROOT . "migration/$name.php";
		$class = Strings::camelize($name) . 'Migration';
		return new $class;
	}

	/** @return Migration[] */
	public function pending() {
		$all = glob($this->dir . DIRECTORY_SEPARATOR . '*');
		$applied = $this->db->migrations->all()->assocs('id');
		$pending = array();

		foreach ($all as $id)
			if (empty($applied[$id]) && preg_match('/\d{8}-\d{4}-(\w+)\.php/i', $id, $m)) {
				require $id;
				$class = Strings::camelize($m[1]) . 'Migration';
				$pending[] = new $class($id, $this->db);
			}

		return $pending;
	}

	public function install() {
		foreach ($this->pending() as $migration)
			$migration->apply();
	}
}