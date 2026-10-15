<?php
/* Add the verbs and nouns values to the already said detection variable */
$alreadySaidSignature = '';
foreach($sentenceWords as $wordIndex => $wordInfo){
	if(isset($wordInfo['cgram']) && $wordInfo['cgram'] == 'VER'){
		$alreadySaidSignature .= $wordInfo['ortho'];
	}
}
foreach($sentenceWords as $wordIndex => $wordInfo){
	if(isset($wordInfo['cgram']) && $wordInfo['cgram'] == 'NOM'){
		$alreadySaidSignature .= $wordInfo['ortho'];
	}
}
