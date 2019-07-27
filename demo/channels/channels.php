<?php
	include('../sessions/save_sessions.php');
	include('../sessions/use_sessions.php');
	$channels = $_POST['channels'];
	write_session('channels', $channels);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>