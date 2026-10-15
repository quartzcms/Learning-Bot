<?php
$storedSyntaxHistory = use_session('stored_syntax');
$sentenceOrder = array();

//This foreach test if the modele match the number of response type values or if response has any value for the modele value
foreach($syntaxModel as $syntaxModelKey => $syntaxModelValue){
	$keptIndexes = array();
	$recreatedWords = array();
	$recreatedWordsNoPlus = array();
	$modelTags = explode(',', $syntaxModelValue);
	$modelRejected = 0;

	require $ai_module_dir . '/patterns/scan_tags.php';

	/* Is validation is still good while creating the new simple modele array */
	if($modelRejected ==  0){
		$agreementRejected = 0;
		require $ai_module_dir . '/patterns/check_agreement.php';

		if($agreementRejected == 0){
			$keptIndexesByType = array();
			$modelFailed = 0;
			$badConnection = 0;

			require $ai_module_dir . '/patterns/validate_pattern.php';
			if($modelFailed == 0 && $historyMatchCount < 1){
				$sentenceOrder = $modelTags;
				$storedSyntaxHistory[] = array('syntax' => $syntaxModelValue, 'human' => $wordsByType);
				write_session('stored_syntax', $storedSyntaxHistory);
				break;
			}
		}
	}
}
