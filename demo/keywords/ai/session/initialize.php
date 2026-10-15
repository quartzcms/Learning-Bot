<?php
$botType = $_POST['type'];
/* If response counter is not defined, set it to 0 */
if(!use_session('count_response_'.$botType)){ 
	write_session('count_response_'.$botType, 0); 
} 
/* Incrementing the response counter +1 */
$responseCount = use_session('count_response_'.$botType);
write_session('count_response_'.$botType, $responseCount + 1);

/* If the short term memory is not defined */
if(!use_session('links_'.$botType)){
	/* Define new array for each accepted word type */
	$acceptedWordTypes = array('other', 'nom', 'ver', 'adj');
	$emptyLinksTable = array();
	foreach($acceptedWordTypes as $typeIndex => $linkTypeName) {
		$emptyLinksTable[$linkTypeName] = array();
	}
	write_session('links_'.$botType, $emptyLinksTable);
}

/* Retake last human question if current human question is a simple yes or no */
$isNegativeSentence = 0;
if(isset($_POST['question']) && !empty($_POST['question']) && use_session('last_human_question_'.$botType)){
	$humanQuestionWords = explode(' ', $_POST['question']);
	if(in_array('oui', $humanQuestionWords) || in_array('non', $humanQuestionWords)){
		$_POST['question'] = use_session('last_human_question_'.$botType);
	}
	if(in_array('non', $humanQuestionWords) || in_array('pas', $humanQuestionWords)){
		/* Used for later (memory delete) */
		$isNegativeSentence = 1;
	}
}
write_session('last_human_question_'.$botType, $_POST['question']);
