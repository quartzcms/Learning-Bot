<?php
/* Send a POST request to an internal service and return the raw response */
function post_service_request($serviceUrl, $postFields) {
	$curlHandle = curl_init($serviceUrl);
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postFields);
	$rawResponse = curl_exec($curlHandle);
	curl_close($curlHandle);
	return $rawResponse;
}

/* Send a GET request and return the raw response */
function get_service_request($requestUrl) {
	$curlHandle = curl_init($requestUrl);
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
	$rawResponse = curl_exec($curlHandle);
	curl_close($curlHandle);
	return $rawResponse;
}

/* Translate a sentence with the Google translate service. Returns the sentence unchanged when no translation is needed or available */
function translate_sentence($sentence, $targetLanguage, $baseUrl) {
	$rawResponse = post_service_request($baseUrl . 'demo/google/translate/api.php', [
		'translate' => $sentence,
		'language' => $targetLanguage
	]);
	if(empty($rawResponse)) {
		return $sentence;
	}
	$translation = json_decode($rawResponse, true);
	return html_entity_decode($translation['translated_text'], ENT_QUOTES);
}

/* Translate the bot response back to the human language if Google translate is enabled */
function translate_reply($sentence, $baseUrl, $translationEnabled) {
	$language = use_session('language');
	if(!$language || $language == 'fr' || $translationEnabled != 1) {
		return $sentence;
	}
	return translate_sentence($sentence, $language, $baseUrl);
}

/* Glue each one letter word to the next word with an apostrophe */
function glue_apostrophes($sentence) {
	static $elidedWords = ['j', 'l', 't', 's', 'm', 'c', 'd', 'n', 'qu', 'puisqu', 'lorsqu', 'quelqu', 'jusqu'];
	$words = explode(' ', $sentence);
	foreach($words as $wordIndex => $word) {
		if(in_array($word, $elidedWords, true)) {
			$words[$wordIndex] = $word . '\'';
		}
	}
	return str_replace('\' ', '\'', implode(' ', $words));
}

/* Store the chatbot response inside the chatbox messages (when having a multiple convo with the chatbot) */
function save_chatbox_message($databaseConnection, $message) {
	$chatboxIds = explode('-', $_POST['ids']);
	session_start();
	mysqli_query($databaseConnection, "INSERT INTO cms_messages (message, date, time, username, group_id, box_id, timestamp_key) VALUES ('".addslashes($message)."', '".date('Y-m-d')."', '".date('H:i:s')."', 'Julie', '".addslashes($chatboxIds[0])."', '".addslashes($chatboxIds[1] ?? '')."', '".round(microtime(true) * 1000)."')") or die (mysqli_error($databaseConnection));
}
