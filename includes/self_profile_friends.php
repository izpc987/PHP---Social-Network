<?php 

	$sql_get_friends = "SELECT * FROM friends WHERE (user1 = '{$row["id"]}' AND level = '1') OR (user2 = '{$row["id"]}' AND level = '1') ORDER BY RAND() LIMIT 8";
	$query_get_friends = $db->query($sql_get_friends);
	$number_of_friends = $query_get_friends->num_rows;

 ?>

<div class="col-md-12 styled-col">
	<h4 class="text-center">Friends (<?php echo $number_of_friends; ?>)</h4><br>
	<div class="friends-list">
		<?php 
			//countinues from $sql_Get_friends
			if ($number_of_friends > 0) {
			while ($row_get_friends = $query_get_friends->fetch_assoc()) {
				if ($row_get_friends["user2"] != $row["id"]) {
					$user = User::get_id($row_get_friends["user2"]);

					$img_name = $user->image;
					$img;
					if ($img_name == "") {
						$img = "http://via.placeholder.com/75x75";
					} else {
						$img = "users/$user->username/$user->image";
					}

					echo "<div class='friend'>
							<a href='profile.php?u=$user->username'>
								<div style='height: 75px; width: 75px; overflow:hidden; border-radius: 100px'>
									<img class='friend-pic' src='$img'>
								</div>
								<p>$user->username</p>
							</a>
						</div>";
				} else if ($row_get_friends["user1"] != $row["id"]) {
					$user2 = User::get_id($row_get_friends["user1"]);

					$img_name = $user2->image;
					$img;
					if ($img_name == "") {
						$img = "http://via.placeholder.com/75x75";
					} else {
						$img = "users/$user2->username/$user2->image";
					}

					echo "<div class='friend'>
							<a href='profile.php?u=$user2->username'>
								<div style='height: 75px; width: 75px; overflow:hidden; border-radius: 100px'>
									<img class='friend-pic' src='$img'>
								</div>
								<p>$user2->username</p>
							</a>
						</div>";
				} ?>

		<?php } } else {
			echo $u . " has no friends yet";
		} ?>
	</div>
</div>