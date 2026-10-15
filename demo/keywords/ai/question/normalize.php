<?php
$reason = $_POST['question'];
/* Separate all the human sentence pronouns from their verbs */
$_POST['question'] = trim($_POST['question'], ' ');
$_POST['question'] = str_replace(
	array('-t', '-il', '-elle', '-tu', '-je', '-nous', '-vous', '-on', '-toi', '-moi', '-le', '-la', '-les'),
	array(' t', ' il', ' elle', ' tu', ' je', ' nous', ' vous', ' on', ' toi', ' moi', ' le', ' la', ' les'),
	$_POST['question']
);
/* Clean unnecessary characters */
$_POST['question'] = str_replace(array('.', ':', ';', ','), '', $_POST['question']);
$_POST['question'] = mb_strtolower($_POST['question'], 'UTF-8');
/* Make the sentence into an array */
$apostropheWords = preg_split("/[\s]/", trim(str_replace('?', '', $_POST['question']), ' '));
$_POST['question'] = str_replace('\'', ' ', $_POST['question']);
$questionWords = preg_split("/[\s]/", trim(str_replace('?', '', $_POST['question']), ' '));
foreach($apostropheWords as $apostropheIndex => $apostropheWord){
	if(strpos($apostropheWord, '\'') === false){
		unset($apostropheWords[$apostropheIndex]);
	}
}

foreach($questionWords as $wordIndex => $wordText){
	if(isset($questionWords[$wordIndex]) && isset($questionWords[$wordIndex + 1])){
		if(in_array($questionWords[$wordIndex].'\''.$questionWords[$wordIndex + 1], $apostropheWords) && strlen($questionWords[$wordIndex]) != 1){
			if($questionWords[$wordIndex + 1] != 'il' && $questionWords[$wordIndex + 1] != 'elle' && 
			$questionWords[$wordIndex + 1] != 'ils' && $questionWords[$wordIndex + 1] != 'elles' &&
			$questionWords[$wordIndex + 1] != 'on' && $questionWords[$wordIndex] != 'qu' && 
			$questionWords[$wordIndex] != 'quelqu') {
				$mergedWord = $questionWords[$wordIndex].'\''.$questionWords[$wordIndex + 1];
				$questionWords[$wordIndex] = $mergedWord;
				unset($questionWords[$wordIndex + 1]);
			}
		}
	}
}
$questionWords = array_values($questionWords);

$questionWords = array_filter($questionWords, function($value) { return $value !== ''; });
$sentenceWords = array();
/* Free card to make the AI learn if * character is used */
if (($starIndex = array_search('*', $questionWords)) !== false) {unset($questionWords[$starIndex]); $hasFreeCard = 1;}

$questionWords = array_values($questionWords);
$test = array();
