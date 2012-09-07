<?php
require_once('core.php');
$action = $_REQUEST['action'];
switch($action){
	default:
		die('Illegal operation!');
	break;
	case 'add_message':
		$room_id = intval($_POST['room_id']);
		$message = $_POST['message'];
		$args = array(
						'user_id' => '1',
						'room_id' => $room_id,
						'content' => $message,
						'timestamp' => time()
					);
		if($db->insert('messages',$args)){
			$output = array('status' => 'ok',
							'time' => time());
				die(json_encode($output));
		}else{
			$output = array('status' => 'failed','time' => time());
				die(json_encode($output));
		}
	break;
	case 'get_messages':
		$last_checked = $db->secure($_GET['last_checked']);
		$room_id = intval($_GET['room_id']);
		$args = array(
						'table' => 'messages',
						'condition' => 'timestamp > '.$last_checked
					);
		if(($result = $db->select($args)) !== false){
			$output = array('status' => 'ok',
							'time' => time(),
							'data' => $result);
				die(json_encode($output));
		}else{
			$output = array('status' => 'failed','time' => time());
				die(json_encode($output));
		}
	break;
	case 'join_room':
		$username = $db->secure($_POST['username']);
		$room_id = intval($_POST['room_id']);
		$args = array(
						'table' => 'rooms',
						'condition' => sprintf('room_id = "%d"',$room_id)
					);
		if(($row = $db->row($args)) == false){
			$out = array('status' => 'failed', 'error' => 'no such room exists');
			die(json_encode($out));
		}
		$args = array(
						'table' => 'users',
						'condition' => sprintf('room_id = "%d" AND user_ip = "%s"',$room_id,$_SERVER['REMOTE_ADDR'])
					);
		if(($row = $db->row($args))){
			$out = array('status' => 'ok', 'room_id' => $room_id);
			die(json_encode($out));
		}
		$args = array(
						'nickname' => $username,
						'joined' => time(),
						'room_id' => $room_id,
						'user_ip' => $_SERVER['REMOTE_ADDR'],
					);
		if($db->insert('users',$args)){
			
			$args = array(
						'user_id' => 0,
						'room_id' => $room_id,
						'content' => ,
						'timestamp' => time()
					);
			$out = array('status' => 'ok', 'room_id' => $room_id);
			die(json_encode($out));
		}
	break;
}
