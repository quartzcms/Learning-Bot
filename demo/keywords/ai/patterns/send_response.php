<?php
$responseSentence = translate_reply(glue_apostrophes($responseSentence), $url, $google_translate);
$responseSentence = ucfirst($responseSentence);

$jsonResult['analyse']['will_learn'] = use_session('last_question_sentence_'.$botType);

/* If this response is a question add it to the last question sentence session */
if(strpos($responseSentence, '?') !== false){
	write_session('last_question_'.$botType, 1);
	write_session('last_question_sentence_'.$botType, $responseSentence);
}

/* If the chatbot found a special pronouns and a Wikipedia description set it to be shown to the human */
if(!empty($appendToResponse)) {
	$responseSentence = $appendToResponse;
}

/* Variable to indicate if the response is an object of a simple text */
if($_POST['nojson'] == 1){
	if(isset($_POST['chatbox']) && $_POST['chatbox'] == 'save'){
		save_chatbox_message($databaseConnection, $responseSentence);
	}
	echo $responseSentence;
} else {
	/* Otherwise echo a JSON object */
	$jsonResult['response'] = $responseSentence;
	$jsonResult['analyse']['detect'] = 'Syntax Patterns';
}
