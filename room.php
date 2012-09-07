<?php require('core.php'); ?>
<?php
$room_id = isset($_GET['room']) ? intval($_GET['room']) : die('No room id specified!');
?>
<!DOCTYPE html >
<html>
	<head>
		<meta charset="utf-8" />
		<title>room</title>
		<script src="js/jquery-1.8.1.min.js"></script>
		<script>
			last_checked = 0;
			$(function(){
				$('#chat-form').submit(function(){
					var room_id = $('input[name=room_id]').val();
					var message = $('input[name=message]').val();
					var params = 'action=add_message&room_id='+room_id+'&message='+message;
					$.post('ajax.php',params,function(data){
						var r = $.parseJSON(data);
						if(r.status == 'ok'){
							last_checked = r.time;
							var new_message = $('<div class="message"><span class="chat-user">User 1:</span> <span class="chat-message">'+message+'</span></div>');
							$('.chat-area').append(new_message);
							$('input[name=message]').val('')
						}
					});
					return false;
				});
			});
			timer = setInterval(function(){
					var params = 'action=get_messages&last_checked='+last_checked+'&room_id='+$('input[name=room_id]').val();
					$.get('ajax.php',params, function(data){
						var r = $.parseJSON(data);
						last_checked = r.time;
						for(i in r.data){
							var user_id = r.data[i].messages.user_id;
							var content = r.data[i].messages.content;
							var new_message = $('<div class="message"><span class="chat-user">User '+user_id+':</span> <span class="chat-message">'+content+'</span></div>');
							$('.chat-area').append(new_message);
						}
					});
				},3000);
		</script>
		<style>
			.chat-area{
				border:1px solid;
				height:500px;
				width:500px;
				marign:10px;
			}
		</style>
	</head>
	<body>
		<div class="chat-area">
		</div><!--.chat-area-->
		<form id="chat-form">
			<input type="text" name="message" id="message" />
			<input type="hidden" name="room_id" value="<?php echo $room_id; ?>" />
		</form>
	</body>
</html>
