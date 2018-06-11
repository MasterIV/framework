<?php

namespace Iv\Framework\Database;

abstract class Migration {
	private $id;
	private $db;

	/**
	 * Migration constructor.
	 * @param $id
	 * @param $db
	 */
	public function __construct($id, $db) {
		$this->id = $id;
		$this->db = $db;
	}

	public function apply($force = false) {
		if(!$force && $this->db->migrations->row($this->id)) {
			echo get_class($this) . " is already applied.\n";
		} else {
			echo "Installing " . get_class($this) . "...\n";
			$this->install($this->db);
			$this->db->migrations->insert(['id' => $this->id], 'REPLACE');
			echo "\tInstallation complete.\n";
		}
	}

	public function revert($force = false) {
		if(!$force && !$this->db->migrations->row($this->id)) {
			echo get_class($this) . " is not installed.\n";
		} else {
			echo "Removing " . get_class($this) . "...\n";
			$this->remove($this->db);
			$this->db->migrations->delRow($this->id);
			echo "\tRemoval complete.\n";
		}
	}

	protected abstract function install(Connection $db);
	protected abstract function remove(Connection $db);
}