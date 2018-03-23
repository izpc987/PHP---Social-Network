<?php 


class Session {

	public $user_id;
	private $is_signed_in = false;

	function __construct() {
		session_start();
		$this->check_the_login();
	}

	public function check_the_login() {
		if (isset($_SESSION["user_id"])) {
			$this->user_id = $_SESSION["user_id"];
			$this->is_signed_in = true;
		} else {
			unset($this->user_id);
			$this->is_signed_in = false;
		}
	}

	public function login($user) {
		if ($user) {
			if ($user->activation == 1) {

				$sql = "UPDATE users SET last_login = now() WHERE id = '{$user->id}' LIMIT 1";
				global $db;
				$query = $db->query($sql);
				$this->user_id = $_SESSION["user_id"] = $user->id;
				$this->is_signed_in = true;
				return true;
			} else {
				return false;
			}
		}
	}

	public function logout() {
		if ($this->logged_in()) {
			unset($this->user_id);
			unset($_SESSION["user_id"]);
			$this->is_signed_in = false;
		}
	}

	public function logged_in() {
		return ($this->is_signed_in) ? true : false;
	}

}

$session = new Session();

?>