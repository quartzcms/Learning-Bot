<?php
/* For each posessive adjective word replace by opposite for example : your -> my */
foreach($sentenceWords as $wordIndex => $wordInfo){
	if(isset($wordInfo['cgram']) && isset($wordInfo['ortho']) && ($wordInfo['cgram'] == 'ADJ:pos' || $wordInfo['cgram'] == 'PRO:pos')){
		$adjectivePossessiveQuery = mysqli_query($databaseConnection, "SELECT * FROM adj_pos WHERE keyword = '".addslashes($wordInfo['ortho'])."' COLLATE utf8_bin LIMIT 1") or die (mysqli_error($databaseConnection));
		if(mysqli_num_rows($adjectivePossessiveQuery) > 0){
			$possessiveRow = mysqli_fetch_assoc($adjectivePossessiveQuery);
			$sentenceWords[$wordIndex]['new'] = $possessiveRow['match'];
		}
	} 
}

/* Deciding randomly if the response will be a suggestion or a repetition */
$actions = array('sug', 'rep');
$randomIndex = rand(0,1);
$randomIndex = (strpos($_POST['question'], '?') !== false) ? 0 : $randomIndex;
/* Defining a new array for verbs */
$verbs = [];
$memoryInsert = 0;
