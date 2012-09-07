<?php
require('core.php');
?>
<!DOCTYPE html >
<html>
	<head>
		<meta charset="utf-8" />
		<title>room</title>
		<script src="js/jquery-1.8.1.min.js"></script>
		<script>
			function join_room(rid){
				var name = prompt("Enter a Nickname please:");
				params = 'action=join_room&username='+name+'&room_id='+rid;
				$.post('ajax.php',params,function(data){
					var r = $.parseJSON(data);
					if(r.status == 'ok'){
						window.location = 'room.php?room='+r.room_id;
					}
				});
			}
		</script>
		<style>
		</style>
	</head>
	<body>
		<?php
			echo 'List of available rooms:';
			$rooms = $db->select(array('table' => 'rooms'));
			echo '<ul class="room-list">';
			foreach($rooms as $room){
				printf('<li>%1$s - <a href="#" onclick="join_room(%2$d);return false;">Join</a></li>',$room['rooms']['room_name'],$room['rooms']['room_id']);
			}
		?>
	</body>
</html>


