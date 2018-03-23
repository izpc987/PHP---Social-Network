<?php 

require_once("../classes/init.php");

$remove_unactive_users = User::delete_unactivated_users();

?>