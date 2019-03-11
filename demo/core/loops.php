<?php

	/******* LOOPS *******/	
	$other = isset($class_other) ? $class_other->loop($response, $response_temp)  : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($other, EXTR_OVERWRITE);
	$nom = isset($class_nom) ? $class_nom->loop($response, $response_temp)  : array(
		'response' => $response,
		'response_temp' => $response_temp
	);
	extract($nom, EXTR_OVERWRITE);
	$pro_per = isset($class_pro_per) ? $class_pro_per->loop($response, $response_temp) : array(
		'response' => $response, 
		'build_memory' => $build_memory 
	);
	extract($pro_per, EXTR_OVERWRITE);
	$adv = isset($class_adv) ? $class_adv->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($adv, EXTR_OVERWRITE);
	$ono = isset($class_ono) ? $class_ono->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($ono, EXTR_OVERWRITE);
	$adj = isset($class_adj) ? $class_adj->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($adj, EXTR_OVERWRITE);
	$art_def = isset($class_art_def) ? $class_art_def->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($art_def, EXTR_OVERWRITE);
	$art_ind = isset($class_art_ind) ? $class_art_ind->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($art_ind, EXTR_OVERWRITE);
	$con = isset($class_con) ? $class_con->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($con, EXTR_OVERWRITE);
	$adj_pos = isset($class_adj_pos) ? $class_adj_pos->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($adj_pos, EXTR_OVERWRITE);
	$aux = isset($class_aux) ? $class_aux->loop($response, $question_array, $verbs, $response_temp) : array(
		'response' => $response,
		'verbs' => $verbs,
		'response_temp' => $response_temp
	);
	extract($aux, EXTR_OVERWRITE);
	$ver = isset($class_ver) ? $class_ver->loop($response, $question_array, $verbs, $response_temp) : array(
		'response' => $response,
		'verbs' => $verbs,
		'response_temp' => $response_temp
	);
	extract($ver, EXTR_OVERWRITE);
	$verb_temp = array();
	
	foreach($path_array as $key => $value){
		foreach($verbs as $index => $verb){
			if($path_array[$key]['ortho'] == $verbs[$index]['ortho']){
				$verb_temp[] = $verbs[$index];
			}
		}
	}
	$verbs = $verb_temp;
	$art = isset($class_art) ? $class_art->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($art, EXTR_OVERWRITE);
	$lia = isset($class_lia) ? $class_lia->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($lia, EXTR_OVERWRITE);
	$pre = isset($class_pre) ? $class_pre->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($pre, EXTR_OVERWRITE);
	$pro = isset($class_pro) ? $class_pro->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($pro, EXTR_OVERWRITE);
	$adj_dem = isset($class_adj_dem) ? $class_adj_dem->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($adj_dem, EXTR_OVERWRITE);
	$adj_ind = isset($class_adj_ind) ? $class_adj_ind->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($adj_ind, EXTR_OVERWRITE);
	$adj_int = isset($class_adj_int) ? $class_adj_int->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($adj_int, EXTR_OVERWRITE);
	$adj_num = isset($class_adj_num) ? $class_adj_num->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($adj_num, EXTR_OVERWRITE);
	$pro_dem = isset($class_pro_dem) ? $class_pro_dem->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($pro_dem, EXTR_OVERWRITE);
	$pro_ind = isset($class_pro_ind) ? $class_pro_ind->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($pro_ind, EXTR_OVERWRITE);
	$pro_int = isset($class_pro_int) ? $class_pro_int->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($pro_int, EXTR_OVERWRITE);
	$pro_pos = isset($class_pro_pos) ? $class_pro_pos->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($pro_pos, EXTR_OVERWRITE);
	$pro_rel = isset($class_pro_rel) ? $class_pro_rel->loop($response, $response_temp) : array(
		'response' => $response, 
		'response_temp' => $response_temp
	);
	extract($pro_rel, EXTR_OVERWRITE);
	/*******************/