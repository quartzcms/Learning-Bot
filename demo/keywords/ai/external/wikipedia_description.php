<?php
$extractedKeywords = json_decode($naturalLanguageResponse, true); 
usort($extractedKeywords, function ($leftWord, $rightWord) {
	return strlen($rightWord) - strlen($leftWord);
});

$wikipediaDescriptions = array();

/* Loop through all the google natural language filtered words */
foreach($extractedKeywords as $keywordIndex => $keyword){
	/* Making sure its only one word */
	if(strpos($extractedKeywords[$keywordIndex], ' ') === false) {
		/* Query wikipedia for the word description */
		$wikipediaApiEndpoint = "https://".$wikipediaLanguage.".wikipedia.org/w/api.php";
		$wikipediaQueryParams = [
			"action" => "query",
			"prop" => "extracts",
			"exlimit" => "max",
			"explaintext" => "",
			"exintro" => "",
			"titles" => $extractedKeywords[$keywordIndex],
			"redirects" => "",
			"format" => "json"
		];

		$wikipediaRequestUrl = $wikipediaApiEndpoint . "?" . http_build_query($wikipediaQueryParams);

		$curlHandle = curl_init($wikipediaRequestUrl);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
		$wikipediaRawResponse = curl_exec($curlHandle);
		curl_close($curlHandle);
		$wikipediaData = json_decode($wikipediaRawResponse, true);

		if(isset($wikipediaData['query']) && isset($wikipediaData['query']['pages'])) {
			foreach($wikipediaData['query']['pages'] as $pageId => $page){
				if(isset($wikipediaData['query']['pages'][$pageId]['extract'])){
					$extract = preg_replace('/\\([^)]*\)/', '', $wikipediaData['query']['pages'][$pageId]['extract']);
					$extract = preg_replace('/\\[[^]]*\]/', '', $extract);
					$extract = explode('. ', $extract);
					$wikipediaDescriptions[] = html_entity_decode($extract[0], ENT_QUOTES);
					break;
				}
			}
			/* Storing first description in an array */
			break; 
		}
	}
}
if(!empty($wikipediaDescriptions)){
	/* Description to be appended to the response later if the appropriate human question pronouns are found */
	$wikipediaExtract = ' '. implode(' ', $wikipediaDescriptions);
	$appendToResponse =  $wikipediaExtract;
}
