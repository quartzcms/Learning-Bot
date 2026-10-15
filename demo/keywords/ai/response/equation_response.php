<?php
if(!empty($response_equation)){
	$response_equation = translate_reply($response_equation, $url, $google_translate);
	$response_equation = ucfirst($response_equation);

	$jsonResult['analyse']['will_learn'] = use_session('last_question_sentence_'.$botType);

	/* If this response is a question */
	if(strpos($response_equation, '?') !== false){
		write_session('last_question_'.$botType, 1);
		write_session('last_question_sentence_'.$botType, $response_equation);
	}

	if($_POST['nojson'] == 1){
		if(isset($_POST['chatbox']) && $_POST['chatbox'] == 'save'){
			save_chatbox_message($databaseConnection, $response_equation);
		}
		echo $response_equation;
	} else {
		/* Echo full JSON object */
		$jsonResult['response'] = $response_equation;
		$jsonResult['analyse']['pattern_chosen'] = 'Pattern is empty';
		$jsonResult['analyse']['detect'] = 'Database pattern';
	}
} else if($_POST['nojson'] != 1){
	// Equation is empty: output nothing in plain text mode
	$jsonResult['response'] = '';
	$jsonResult['analyse']['pattern_chosen'] = 'Pattern is empty';
	$jsonResult['analyse']['detect'] = 'Equation pattern';
}
