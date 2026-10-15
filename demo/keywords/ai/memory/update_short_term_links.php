<?php
/****************** FOR KEYWORDS ARRAY ****************/
$shortTermLinks = array();
if(use_session('links_'.$botType)){
	$shortTermLinks = use_session('links_'.$botType);
}

$linkAlreadyKnown = 0;
if(isset($sentenceWords) && !empty($sentenceWords)){
	foreach($sentenceWords as $wordIndex => $wordInfo){
		foreach($shortTermLinks as $existingLinkType => $linkWords){
			if(in_array($sentenceWords[$wordIndex]['ortho'], $linkWords)){
				$linkAlreadyKnown = 1;
			}
		}
	}
}
if($linkAlreadyKnown == 0){
	$acceptedWordTypes = array('other', 'nom', 'ver', 'adj');
	$emptyLinksTable = array();
	foreach($acceptedWordTypes as $typeIndex => $linkTypeName) {
		 $emptyLinksTable[$linkTypeName] = array();
	}
	write_session('links_'.$botType, $emptyLinksTable);
}

/******************************************************/

/* If no special pronoun are found the chatbot will not output Wikipedia description to the human */
$hasInterrogativePronoun = 0;
foreach($sentenceWords as $wordIndex => $wordInfo){
	if(isset($wordInfo['cgram']) && $wordInfo['cgram'] == 'PRO:int'){
		$hasInterrogativePronoun = 1;
		break;
	}
}
if($hasInterrogativePronoun == 0){
	$appendToResponse = '';
}

/* Store the new human questions words in the short term memory session */
$acceptedWordTypes = array('other', 'nom', 'ver', 'adj');
$updatedLinks= use_session('links_'.$botType);
foreach($acceptedWordTypes as $typeIndex => $linkType) {
	foreach($sentenceWords as $wordKey => $wordValue){
		$wordType = str_replace(':', '_', mb_strtolower($wordValue['cgram'], 'UTF-8'));
		if(isset($updatedLinks[$linkType]) && $linkType == $wordType && !in_array($wordValue['ortho'], $updatedLinks[$linkType])){
			$updatedLinks[$linkType][] = $wordValue['ortho'];
		}
	}
}
write_session('links_'.$botType, $updatedLinks);
