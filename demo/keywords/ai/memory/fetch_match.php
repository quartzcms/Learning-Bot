<?php
/* Querying the database for a match */
$memoryQuery = mysqli_query($databaseConnection, "SELECT * FROM ai_memory_".$botType." ".$query." ORDER BY id DESC LIMIT 1") or die (mysqli_error($databaseConnection));
$analysisData = mysqli_fetch_assoc($memoryQuery);

if(mysqli_num_rows($memoryQuery) > 0){
	/* If a match is found store the ID in the used IDs array session */
	$usedMemoryIds = use_session('used_id_'.$botType);
	$usedMemoryIds[] = $analysisData['id'];
	write_session('used_id_'.$botType, $usedMemoryIds);
	$randWords = rand(1, 5);

	if(!empty($analysisData)){
		/* Getting the pattern outputed for side block data */
		$analysisData['pattern'] = str_replace(' {', '{', $analysisData['pattern']);
		$analysisData['pattern'] = str_replace('} ', '}', $analysisData['pattern']);
		$memoryResponse = preg_split('/[\{,\}]/', $analysisData['pattern']);
		$memoryPatternTags = implode(',', array_filter($memoryResponse, function($value) { return $value !== ''; }));
	}

	if(!empty($analysisData)){
		/* Add new verbs and nouns to the already said detection variable */
		$alreadySaidSignature = '';
		$verForms = json_decode($analysisData['ver'], true);
		$nounForms = json_decode($analysisData['nom'], true);
		$alreadySaidSignature .= (is_array($verForms) ? implode($verForms) : '');
		$alreadySaidSignature .= (is_array($nounForms) ? implode($nounForms) : '');
		/* Store the bot response in the pattern variable */
		$memoryResponse = $analysisData['human'];
	}
}
