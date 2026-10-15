<?php
/* Looping through all the question words again */
foreach($questionWords as $wordIndex => $wordText) {
	$storedWordForms = array();
	$nextWord = $questionWords[$wordIndex + 1] ?? null;
	$previousWord = $questionWords[$wordIndex - 1] ?? null;
	$wordHash = md5($wordText);
	if(isset($analysisData[$wordHash])){
		foreach($analysisData[$wordHash] as $dictionaryEntry) {
			$isWordValid = 1;
			/* Types already kept for the previous word (changes while repeated words are stored) */
			$previousWordTypes = ($previousWord !== null) ? ($keptWordTypes[$previousWord] ?? null) : null;

			require $ai_module_dir . '/analysis/neighbor_syntax.php';
			require $ai_module_dir . '/analysis/verb_exclusions.php';
			require $ai_module_dir . '/analysis/store_word_match.php';
		}
	}
	if(empty($storedWordForms) || !isset($analysisData[$wordHash])){
		if($wordText !== 'julie' || $wordText !== 'sandra'){
			require $ai_module_dir . '/analysis/unknown_word.php';
		}
	}
}
