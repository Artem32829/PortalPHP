
<?php
echo'
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="chatcss.css">
</head>';
echo'
<body>
<div class="chat">
	<div class="chat-messages">
		<div class="chat-messages__content" id="messages">
			Загрузка...
		</div>
	</div>
	<div class="chat-input">
		<form method="post" id="chat-form">
			<input type="text" id="message-text" 
            class="chat-form__input" 
            placeholder="Введите сообщение"> 
            <input type="submit" 
            class="chat-form__submit" value="=>">
		</form>
	</div>
</div>
</body>';
?>