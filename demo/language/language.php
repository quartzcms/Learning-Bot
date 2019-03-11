<?php
	session_start();
	$language = $_POST['language'];
	$_SESSION['language'] = $language;
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>