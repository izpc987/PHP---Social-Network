<?php 

require_once("db.php");

class Database {

	public $connection;

	function __construct() {
		$this->create_connection();
	}

	public function create_connection() {
		$this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if (!$this->connection) {
			die($this->connection->error);
		}
	}

	public function query($sql) {
		$query = $this->connection->query($sql);
		if (!$query) {
			die($this->connection->error);
		}

		return $query;
	}

	public function escape_string($string) {
		return $this->connection->real_escape_string($string);
	}

	public function last_id() {
		return $this->connection->insert_id;
	}

}

$db = new Database();


?>