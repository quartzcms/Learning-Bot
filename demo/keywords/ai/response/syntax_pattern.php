<?php
require $ai_module_dir . '/patterns/models.php';
require $ai_module_dir . '/patterns/match_models.php';
if(!empty($sentenceOrder)){
	require $ai_module_dir . '/patterns/reorder_tags.php';
	require $ai_module_dir . '/patterns/build_sentences.php';

	if(!empty($responseSentence)){
		require $ai_module_dir . '/patterns/send_response.php';
	} else {
		// If a pattern is matched but no response : output nothing

		if($_POST['nojson'] == 1){

		} else {
			$jsonResult['response'] = '';
			$jsonResult['analyse']['pattern_chosen'] = 'Pattern is empty';
			$jsonResult['analyse']['detect'] = 'Syntax Patterns';
		}
	}
} else {
	// If no sentence pattern matched : output nothing

	if($_POST['nojson'] == 1){

	} else {
		$jsonResult['response'] = '';
		$jsonResult['analyse']['pattern_chosen'] = 'No pattern matched';
		$jsonResult['analyse']['detect'] = 'Syntax Patterns';
	}
}
