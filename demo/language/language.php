<?php
	include('../sessions/save_sessions.php');
	include('../sessions/use_sessions.php');
	$language = $_POST['language'];
	write_session('language', $language);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>