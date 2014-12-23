<?php


namespace Iv\Framework\Mysql;

class Migration {
	/** @var Connection */
	private $db;
	/** @var string */
	private $dir;

	function __construct($db, $dir = 'migration/') {
		$this->db = $db;
		$this->dir = $dir;
	}

	public function install( $file ) {
		$this->apply( $file, 'install' );
	}

	public function remove( $file ) {
		$this->apply( $file, 'remove' );
	}

	private function apply( $file, $type ) {
		require $this->dir.$file;
		if( $type == 'remove' ) remove(); else install();
		$this->checkTable();
		$this->db->migration->insert(array( 'id' => $file ));
	}

	public function create( $file ) {
		file_put_contents( $this->dir.date('Ymd-Hi-').$file.'.php',
				"<?php\n\nfunction install(\$db) {\n\t\$db->query(\"\");\n}\n\nfunction remove(\$db) {\n\t\$db->query(\"\");\n}\n" );
	}

	public function listPending() {
		$this->checkTable();

		$all = glob($this->dir.'*');
		$applied = $this->db->migration->all()->assocs('id');
		$pending = array();

		foreach( $all as $file ) {
			$id = substr( $file, 1+strrpos( $file, '/' ));
			if (empty($applied[$id])) $pending[] = $id;
		}

		return $pending;
	}

	private function checkTable() {
		if( !$this->db->query("show tables like 'migration'")->num_rows()) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `migration` (
				`id` varchar(250) NOT NULL,
				`create_date` int(10) unsigned NOT NULL,
				`create_by` int(10) unsigned NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		}
	}
} 
