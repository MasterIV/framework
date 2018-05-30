<?php

use Iv\Framework\Database\Migration;
use Iv\Framework\Database\Connection;

class MigrationClassName implements Migration {
	public function install(Connection $db) {
		$db->exec("");
	}

	public function remove(Connection $db) {
		$db->exec("");
	}
}