<?php
$wikipediaExtract = '';
$appendToResponse = '';
$wikipediaLookupPending = 0;
/* If google natural language is activated */
if($google_natural_language == 1 && isset($originalQuestion) && !empty($originalQuestion)) {

	$translationPostFields = [
		'text' => $originalQuestion
	];

	/* Getting all the meaningfull word from the human sentence */
	$curlHandle = curl_init($url."demo/google/natural_language/api.php");
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $translationPostFields);

	$naturalLanguageResponse = curl_exec($curlHandle);
	curl_close($curlHandle);

	$wikipediaLanguage = '';
	/* Setting the language for Wikipedia query */
	if(use_session('language')) {
		$wikipediaLanguage = use_session('language');
	} else {
		$wikipediaLanguage = 'fr';
	}

	if(!empty($naturalLanguageResponse)) { 
		require $ai_module_dir . '/external/wikipedia_description.php';
	} else {
		$wikipediaLookupPending = 1;
	}
}	
