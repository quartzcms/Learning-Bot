<?php
/* Walk back from the verb to the previous verb, collecting determiners and nouns and detecting a coordination */
for($position = max($verbPosition - 1, 0); $position > -1; $position--){
	$currentWord = $reindexedWords[$position];
	$currentType = $currentWord['cgram'] ?? null;

	if(in_array($currentType, $conjugatedVerbTypes, true)){
		break;
	}

	if(in_array($currentType, $determinerTypes, true)){
		$precedingDeterminers[] = $currentWord;
	} elseif($currentType === 'NOM'){
		$precedingNouns[] = $currentWord;
	}

	if(isset($currentWord['ortho']) && in_array($currentWord['ortho'], $coordinationWords, true)){
		$hasCoordination = 1;
	}
}
