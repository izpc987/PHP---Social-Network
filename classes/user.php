<?php 

class User {

	public static $db_table = "users";

	public $id;
	public $username;
	public $password;
	public $firstname;
	public $lastname;
	public $image;
	public $gender;
	public $email;
	// public $date_created;
	// public $last_login;

	public static function get_all() {
		$sql = "SELECT * FROM " . self::$db_table;
		return self::query($sql);
	}

	public static function get_id($id) {
		$sql = "SELECT * FROM " . self::$db_table . " WHERE id = {$id} LIMIT 1";
		$object_in_array = self::query($sql);

		return array_shift($object_in_array);
	}

	public static function instantiate($result) {
		$obj = new self;

		foreach ($result as $key => $value) {
			$obj->$key = $value;
		}

		return $obj;
	}

	public static function query($sql) {
		global $db;
		$query = $db->query($sql);

		$arr = array();

		while ($row = mysqli_fetch_assoc($query)) {
			$arr[] = self::instantiate($row);
		}

		return $arr;
	}

	public static function checkUsername($username) {
		global $db;

		$sql = "SELECT * FROM users WHERE username = '{$username}'";

		$query = $db->query($sql);

		$number = mysqli_num_rows($query);
		if ($number != 0) {
			return true;
		} else {
			return false;
		}
	}

	public static function checkEmail($email) {
		global $db;

		$sql = "SELECT * FROM users WHERE email = '{$email}'";

		$query = $db->query($sql);

		$number = mysqli_num_rows($query);
		if ($number != 0) {
			return true;
		} else {
			return false;
		}
	}

	public static function verify_user($email, $password) {

		global $db;

		$email = $db->escape_string($email);
		$password = $db->escape_string($password);

		$sql = "SELECT * FROM users WHERE email = '{$email}' AND password = '{$password}' LIMIT 1";
		$query = self::query($sql);
		$first_arr = array_shift($query);

		return $first_arr;

	}

	public static function activateUser($username, $password) {

		global $db;

		$u = $db->escape_string($username);
		$p = $db->escape_string($password);

		$sql = "UPDATE users SET activation = 1 WHERE username = '{$u}' AND password = '{$p}'";

		if ($db->query($sql)) {
			return true;
		} else {
			false;
		}



	}

	public static function delete_user_folder($user) {
		$path = "users/{$user}";
		if (file_exists($path)) {
			rmdir($path);
		}
	}

	public static function delete_unactivated_users() {
		$sql = "SELECT * FROM users WHERE date_created <= CURRENT_DATE - INTERVAL 3 DAY AND activation = 0";
		$users = self::query($sql);
		$number_of_users = count($users);

		if ($number_of_users > 0) {
			foreach($users as $user) {
				global $db;
				$sql = "DELETE FROM users WHERE username = '{$user->username}' AND activation = 0 LIMIT 1";
				$query = $db->query($sql);
				if ($query) {
					return true;
				}
			}
		}
	}


}

?>