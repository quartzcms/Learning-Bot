<?php
	include('../sessions/save_sessions.php');
	include('../sessions/use_sessions.php');
	$captcha = $_POST['captcha'];
	write_session('captcha', $captcha);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>