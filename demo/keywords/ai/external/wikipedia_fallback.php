<?php
if($wikipediaLookupPending == 1){
	$lookupName = '';
	/* Detect nouns in the human question data array */
	foreach($sentenceWords as $wordIndex => $wordInfo){
		if(isset($wordInfo['cgram']) && $wordInfo['cgram'] == 'NOM'){
			$lookupName = $wordInfo['ortho'];
			break;
		}
	}
	/* If a word is found query Wikipedia for a description to be used if the appropriate pronouns are found in human sentence */
	if(!empty($lookupName)){
		/* Query wikipedia for the word description */
		$wikipediaApiEndpoint = "https://".$wikipediaLanguage.".wikipedia.org/w/api.php";
		$wikipediaQueryParams = [
			"action" => "opensearch",
			"search" => $lookupName,
			"limit" => "1",
			"namespace" => "0",
			"format" => "json"
		];

		$wikipediaRequestUrl = $wikipediaApiEndpoint . "?" . http_build_query($wikipediaQueryParams);

		$curlHandle = curl_init($wikipediaRequestUrl);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
		$wikipediaRawResponse = curl_exec($curlHandle);
		curl_close($curlHandle);
		$wikipediaData = json_decode($wikipediaRawResponse, true);

		$wikipediaDescriptions = array();
		if(isset($wikipediaData[2][0]) && !empty($wikipediaData[2][0]) && substr($wikipediaData[2][0], -1) == '.') { 
			$wikipediaDescriptions[] = html_entity_decode($wikipediaData[2][0], ENT_QUOTES); 
		}
		if(!empty($wikipediaDescriptions)){
			$wikipediaExtract = ' '. implode(' ', $wikipediaDescriptions);
			$appendToResponse = $wikipediaExtract;
		}
	}
}
