<?php

use Iv\Framework\Database\Migration;
use Iv\Framework\Database\Connection;

class MigrationClassName extends Migration {
	protected function install(Connection $db) {
		$db->exec("");
	}

	protected function remove(Connection $db) {
		$db->exec("");
	}
}