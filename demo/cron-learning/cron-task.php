<?php
ini_set('display_errors', 1);
include(dirname(__FILE__).'/../sessions/save_sessions.php');
include(dirname(__FILE__).'/../sessions/use_sessions.php');
// Execute this cron task every minute to send to the AI an human like wikipedia sentence
// * * * * * cd /path/to/project/root/demo/cron-learning && php cron-task.php
include(dirname(__FILE__).'/../../config.php');
$connexion = mysqli_connect($al_host, $al_user, $al_password, $al_db_name);
mysqli_set_charset($connexion, 'utf8');
$link_one = use_session('links_one');
if(!use_session('already_one')) { write_session('already_one', 1); }

$accepted = array('nom');
$links = array();
foreach($accepted as $key => $value) {
	if(isset($link_one[$value]) && !empty($link_one[$value])){
		foreach($link_one[$value] as $index => $value2){
			$links[] = 'human LIKE \'%'.addslashes($link_one[$value][$index]).'%\'';
		}
	}
}

$query = '';
if(!empty($links)){
	$query = '('.implode(' COLLATE utf8_bin AND ', $links).') AND';
}

$memory_query = mysqli_query($connexion, "SELECT * FROM ai_memory_one WHERE ".$query." wikipedia != '' AND ip = '255.255.255.255' ORDER BY RAND() LIMIT 1") or die (mysqli_error($connexion));
$data = mysqli_fetch_assoc($memory_query);
$human_sentence = '';

if(!empty($data)){
	$rand = explode('.', trim($data['wikipedia'], '.'));
	$index = rand(0, (count($rand) - 1));
	
	if(!empty($rand[$index])){
		$human_sentence = $rand[$index];
		$human_sentence = str_replace(')', '', $human_sentence);
		$human_sentence = str_replace('(', '', $human_sentence);
		$human_sentence = str_replace('«', '', $human_sentence);
		$human_sentence = str_replace('»', '', $human_sentence);
		$human_sentence = str_replace('[', '', $human_sentence);
		$human_sentence = str_replace(']', '', $human_sentence);
		$human_sentence = str_replace('’', '\'', $human_sentence);
	}
}

if(!empty($human_sentence)){
	$post_values = [
		'type' => 'one',
		'question' => $human_sentence,
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