<?php
	session_start();
	$captcha = $_POST['captcha'];
	$_SESSION['captcha'] = $captcha;
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>