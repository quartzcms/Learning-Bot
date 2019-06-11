<?php

	/******* INCLUDES *******/
	
	$variables = array(
		'build_memory' => $build_memory,
		'connexion' => $connexion,
		'action' => $action,
		'rand' => $rand,
		'type_bot' => $type_bot,
		'question_array' => $question_array
	);
	include('../types/_default.php');
	$default = '_default';
	
	foreach($build_memory as $key => $value) {
		$key = explode(':', strtolower($key));
		if(isset($key[1])){
			$class = $key[0].'_'.$key[1];
			$class_var = 'class_'.$key[0].'_'.$key[1];
			if(isset($key[2])){
				$class = $key[0].'_'.$key[1].'_'.$key[2];
				$class_var = 'class_'.$key[0].'_'.$key[1].'_'.$key[2];
				include('../types/'.$key[0].'/_'.$key[1].'_'.$key[2].'.php');
			} else {
				include('../types/'.$key[0].'/_'.$key[1].'.php');
			}
			if (class_exists($key[0].'_'.$key[1]) || class_exists($key[0].'_'.$key[1].'_'.$key[2])) {
				$$class_var = new $class($variables);
			} else {
				$$class_var = new $default($variables);
			}
		} else {
			$class = $key[0];
			$class_var = 'class_'.$key[0];
			include('../types/_'.$key[0].'.php');
			if (class_exists($key[0])) {
				$$class_var = new $class($variables);
			} else {
				$$class_var = new $default($variables);
			}
		}
	}
	
	/*******************/