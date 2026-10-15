<?php
$memoryResponse = str_replace(' .', '.', $memoryResponse);

if(!empty($memoryResponse)){
	$memoryResponse = translate_reply(glue_apostrophes($memoryResponse), $url, $google_translate);
	$memoryResponse = ucfirst($memoryResponse);

	$jsonResult['analyse']['will_learn'] = use_session('last_question_sentence_'.$botType);

	/* If this response is a question */
	if(strpos($memoryResponse, '?') !== false){
		write_session('last_question_'.$botType, 1);
		write_session('last_question_sentence_'.$botType, $memoryResponse);
	}

	if($_POST['nojson'] == 1){
		if(isset($_POST['chatbox']) && $_POST['chatbox'] == 'save'){
			save_chatbox_message($databaseConnection, $memoryResponse);
		}
		echo $memoryResponse;
	} else {
		/* Echo full JSON object */
		$jsonResult['response'] = $memoryResponse;
		$jsonResult['analyse']['pattern_chosen'] = str_replace(',', ', ', $memoryPatternTags);
		$jsonResult['analyse']['detect'] = 'Database pattern';
	}
} else if($_POST['nojson'] != 1){
	// Pattern is empty: output nothing in plain text mode
	$jsonResult['response'] = '';
	$jsonResult['analyse']['pattern_chosen'] = 'Pattern is empty';
	$jsonResult['analyse']['detect'] = 'Database pattern';
}
