<?php 
	function use_session($session_name) {
		$_SERVER['REMOTE_ADDR'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '255.255.255.255';
		$file_path = __DIR__.'/data/'.$_SERVER['REMOTE_ADDR'].'-'.date('Ymd').'.txt';		
		$content = @file_get_contents($file_path);
		$array_of_session = explode("\n", $content);
		
		foreach($array_of_session as $key => $line){
			$line_name = explode(':', $line);
			if(isset($line_name[0]) && $line_name[0] == $session_name){
				if(isset($line_name[1])){
					return json_decode(base64_decode($line_name[1]), true);
				} else {
					return '';
				}
			}
		}
		return NULL;
	}
	
	


?>