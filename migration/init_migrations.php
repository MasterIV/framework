<?php

use Iv\Framework\Database\Migration;
use Iv\Framework\Database\Connection;

class InitMigrationsMigration extends Migration {
	protected function install(Connection $db) {
		$db->exec("CREATE TABLE IF NOT EXISTS `migrations` (
				`id` varchar(250) NOT NULL,
				`create_date` int(10) unsigned DEFAULT NULL,
				`create_by` int(10) unsigned DEFAULT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	}

	protected function remove(Connection $db) {
		$db->exec("DROP TABLE `migrations`;");
	}
}