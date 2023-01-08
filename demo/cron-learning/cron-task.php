<?php
ini_set('display_errors', 1);
include(dirname(__FILE__).'/../sessions/save_sessions.php');
include(dirname(__FILE__).'/../sessions/use_sessions.php');
// Execute this cron task every minute to send to the AI an human like wikipedia sentence
// * * * * * cd /path/to/project/root/demo/cron-learning && php cron-task.php
include(dirname(__FILE__).'/../../config.php');
$connexion = mysqli_connect($al_host, $al_user, $al_password, $al_db_name);
mysqli_set_charset($connexion, 'utf8');
$link_two = use_session('links_two');

$accepted = array('nom');
$links = array();
foreach($accepted as $key => $value) {
	if(isset($link_two[$value]) && !empty($link_two[$value])){
		foreach($link_two[$value] as $index => $value2){
			$links[] = 'human LIKE \'%'.addslashes($link_two[$value][$index]).'%\'';
		}
	}
}

$query = '';
if(!empty($links)){
	$query = '('.implode(' COLLATE utf8_bin AND ', $links).') AND';
}

$memory_query = mysqli_query($connexion, "SELECT * FROM ai_memory_two WHERE ".$query." wikipedia != '' AND ip = '255.255.255.255' ORDER BY RAND() LIMIT 1") or die (mysqli_error($connexion));
$data = mysqli_fetch_assoc($memory_query);
$wiki = '';

if(!empty($data)){
	$wiki = $data['wikipedia'];
	$wiki = preg_replace('/[^A-Za-z0-9\-àâáçéèèêëìîíïôòóùûüÂÊÎÔúÛÄËÏÖÜÀÆæÇÉÈŒœÙñý\'’.,\s]/', '', $wiki);
}

if(!empty($wiki)){
	$post_values = [
		'type' => 'two',
		'question' => $wiki,
		'nojson' => 0,
		'bot' => 1,
	];	
	/* Website AI file URL: Replace with yours */
	$ch = curl_init($url.'demo/keywords/ai.php');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
	
	$response = curl_exec($ch);
	curl_close($ch);
}	
	
?>