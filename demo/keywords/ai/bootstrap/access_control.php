<?php
/* If language is defined set new language */
if(isset($_POST['language'])){
	write_session('language', $_POST['language']);
}

/* Set captcha if bot variable is true or if the AI script is read from internally by the server */
if($_SERVER['REMOTE_ADDR'] == $server_ip || (isset($_POST['bot']) && $_POST['bot'] == 1)){ 
	write_session('captcha', '15'); 
}
/* If captcha is not set */
if(!use_session('captcha') || use_session('captcha') != '15'){
	/* Return an empty JSON object */
	echo json_encode(['response' => '', 'analyse' => array('', 'words_found' => array(), 'pattern_chosen' => 'No pattern found (empty captcha)', 'none', array(), '', 'empty_', 'will_learn' => '', 'already_said' => 'no')]); 
	exit;
}

/* Get the channel. If not found use the default one. */
if(!use_session('channels')){ 
	write_session('channels', '255.255.255.255');
	$userIp = '255.255.255.255';
} else {
	$userIp = use_session('channels');
}
