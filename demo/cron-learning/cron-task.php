<?php
ini_set('display_errors', 1);
session_start();
// Execute this cron task every minute to send to the AI an human like wiipedia sentence
// * * * * * cd /path/to/project/root && PHP cron-task.php 1>> /dev/null 2>&1
include(dirname(__FILE__).'/../../config.php');
$connexion = mysqli_connect($al_host, $al_user, $al_password, $al_db_name);
mysqli_set_charset($connexion, 'utf8');

if(!isset($_SESSION['already_one'])) { $_SESSION['already_one'] = 1; }
$accepted = array('other', 'nom');
if(isset($_SESSION['links_one'])) {
	$detect_empty = 0;
	foreach($accepted as $key1 => $value1) {
		if(isset($_SESSION['links_one'][$value1]) && empty($_SESSION['links_one'][$value1])){
			$detect_empty = 1;
		}
	}
	if($detect_empty == 0) {
		$_SESSION['already_one'] = 0;
	} else {
		$_SESSION['already_one'] = 1;
	}
}

if($_SESSION['already_one'] = 0){
	$links = array();
	foreach($accepted as $key1 => $value1) {
		if(isset($_SESSION['links_one'][$value1]) && !empty($_SESSION['links_one'][$value1])){
			foreach($_SESSION['links_one'][$value1] as $key => $value){
				$links[] = 'human LIKE \'%'.addslashes($_SESSION['links_one'][$value1][$key]).'%\'';
			}
		}
	}
	
	if(!empty($links)){
		$query_links .= implode(' COLLATE utf8_bin AND ', $links);
	}
		
	$query = 'WHERE ('.$query_links.') AND (pattern LIKE \'%{%\' AND pattern LIKE \'%}%\')';
	
	//$memory_query = mysqli_query($connexion, "SELECT * FROM ai_memory_one ".$query." AND wikipedia != '' AND ip = '".$_SERVER['REMOTE_ADDR']."' ORDER BY RAND() LIMIT 1") or die (mysqli_error($connexion));
	$memory_query = mysqli_query($connexion, "SELECT * FROM ai_memory_one ".$query." AND wikipedia != '' ORDER BY RAND() LIMIT 1") or die (mysqli_error($connexion));
	$data = mysqli_fetch_assoc($memory_query);
} else {
	//Need more than 30 results othewise it could repeat same line
	$memory_query = mysqli_query($connexion, "SELECT * FROM ai_memory_one WHERE wikipedia != '' ORDER BY RAND() LIMIT 1") or die (mysqli_error($connexion));
	$data = mysqli_fetch_assoc($memory_query);
}

$human_sentence = '';

if(!empty($data)){
	$rand = explode('.', $data['wikipedia']);
	
	foreach($rand as $key => $value){
		if(!isset($rand[$key + 1])){
			unset($rand[$key]);
		}
	}
	
	$index = rand(0, (count($rand) - 1));
	if(!empty($rand[$index])){
		$human_sentence = $rand[$index];
		$human_sentence = preg_replace('#\([^\)]*\)#', '', $human_sentence);
		$human_sentence = str_replace('«', '', $human_sentence);
		$human_sentence = str_replace('»', '', $human_sentence);
		$human_sentence = str_replace('[', '', $human_sentence);
		$human_sentence = str_replace(']', '', $human_sentence);
		$human_sentence = preg_replace('[\’]', '\'', $human_sentence);
	}
}
if(!empty($human_sentence)){
	$post_values = [
		'type' => 'one',
		'question' => $human_sentence,
	];
	
	$strCookie = 'PHPSESSID=session; path=/';
	session_write_close();
	
	/* Website AI file URL: Replace with yours */
	$ch = curl_init($url.'demo/keywords/ai.php');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
	curl_setopt($ch, CURLOPT_COOKIE, $strCookie); 
	
	$response = curl_exec($ch);
	curl_close($ch);
}
	
?>