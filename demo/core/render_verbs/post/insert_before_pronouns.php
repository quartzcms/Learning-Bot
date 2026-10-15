<?php
$rewrittenWords = $sentenceWords;
$insertionIndex = 0;
foreach($sentenceWords as $wordKey => $currentWord){
	if(isset($sentenceWords[$wordKey + 1]) && isset($sentenceWords[$wordKey + 1]['insert_before'])){
		array_splice($rewrittenWords, $wordKey, 0, $pronounsToInsert[$insertionIndex]);
		$insertionIndex++;
	}
}
