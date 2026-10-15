<?php
/* Set the human question for Google natural language */
if(isset($_POST['question']) && !empty($_POST['question'])){
	$originalQuestion = $_POST['question'];

	/* If language is set and Google translate is activated, translate the question to french which is the bot language */
	if(use_session('language') && use_session('language') != 'fr' && $google_translate == 1) {
		$_POST['question'] = translate_sentence($_POST['question'], 'fr', $url);
	}
}
