<?php

	function write_session($session_name, $session_content) {
		$_SERVER['REMOTE_ADDR'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '255.255.255.255';
		$file_path = __DIR__.'/data/'.$_SERVER['REMOTE_ADDR'].'-'.date('Ymd').'.txt';
		
		if(!file_exists($file_path)){
			$content = "";
		} else {
			$content = file_get_contents($file_path);
		}
		
		$mode = 'w';
		$array_of_session = explode("\n", $content);
		
		$detect = 0;
		foreach($array_of_session as $key => $line){
			$line_name = explode(':', $line);
			if(isset($line_name[0]) && !empty($line_name[0]) && $line_name[0] == $session_name){
				unset($array_of_session[$key]);
				$line_name[1] = base64_encode(json_encode($session_content));
				$array_of_session[] = implode(':', $line_name);
				$detect = 1;
			}
		}
		if($detect == 0 && $session_name != ''){
			$array_of_session[] = $session_name.":".base64_encode(json_encode($session_content));
		}
		
		$sessions = implode("\n", $array_of_session);
		
		$write = fopen($file_path, $mode);
		fwrite($write, $sessions);
		fclose($write);
	}
	
	

?>