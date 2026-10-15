<?php
/* Array of the final reordered response arrays using pattern modele is ready 
$reorderedWords = array(
	0 => array(
		'tomatoes', <- First element
		'lettuce',
		'onions',
		'carrots',
		'pepper',
		'apple'
	),
	1 => array(
		'are',
		'is',
		'were'
	),
	2 => array(
		'lettuce', <- First new element
		'onions',
		'carrots',
		'pepper',
		'apple'
	), 
	3 => array(
		'and',
		'plus',
		'or'
	), 
	4 => array(
		'onions', <- First new element
		'carrots',
		'pepper',
		'apple'
	), 
	5 => array(
		'is',
		'were'
	), 
	6 => array(
		'carrots', <- First new element
		'pepper',
		'apple'
	),
	7 => '.' 
)
*/
$reorderedWords = array_values($reorderedWords);

//This for loop iterate the modele with each value => array()
//It will store an array each sentence and get each response type value for each new modele value
for($variantIndex = 0; $variantIndex < 11; $variantIndex++){
	$sentenceVariants[$variantIndex] = array();
	foreach($reorderedWords as $slotIndex => $slotValue){
		if(is_array($slotValue)){
			if(!empty($reorderedWords[$slotIndex])){
				if(isset($reorderedWords[$slotIndex][$variantIndex])) {
					/* Reorganize element to get them in order and class them by sentence */
					$sentenceVariants[$variantIndex][$slotIndex] = $reorderedWords[$slotIndex][$variantIndex];
				}
			}
		} else {
			/* For dots and question mark */
			$sentenceVariants[$variantIndex][$slotIndex] = $reorderedWords[$slotIndex];
		}
	}

	if(!empty($sentenceVariants[$variantIndex]) && count($sentenceVariants[$variantIndex]) > 1){
		if(count($reorderedWords) == count($sentenceVariants[$variantIndex])){
			/* Store this sentence in a container array */
			$builtSentences[] = implode(' ', $sentenceVariants[$variantIndex]);
		}
	}
}
/*
	-> Example of build_container
	$builtSentences = array(
		0 => array(
			'tomatoes',
			'are',
			'lettuce',
			'and',
			'onions',
			'is',
			'carrots'
		),
		1 => array(
			'pepper',
			'were',
			'apple',
			'plus'
		)		
	);
*/

$jsonResult['analyse']['pattern_chosen'] = implode(', ', $sentenceOrder);
$jsonResult['analyse']['reorder_array'] = $reorderedWords;
$jsonResult['analyse']['detect'] = 'Syntax Patterns';

$analysisData = $wordsByType;
$responseSentence = (!empty($builtSentences)) ? implode(' ', $builtSentences) : '';
$responseSentence = str_replace('\' ', '\'', $responseSentence);
$responseSentence = str_replace(' .', '.', $responseSentence);

/* If this response from the bot is already store in the last response, set response to empty */
if(in_array($responseSentence, use_session('last_response_'.$botType))){
	$responseSentence = '';
} else {
/* Else add this response to the last response array */
	$lastResponses = use_session('last_response_'.$botType);
	$lastResponses[] = $responseSentence;
	write_session('last_response_'.$botType, $lastResponses);
}
