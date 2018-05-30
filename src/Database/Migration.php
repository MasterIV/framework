<?php

namespace Iv\Framework\Database;

interface Migration {
	public function install(Connection $db);
	public function remove(Connection $db);
}