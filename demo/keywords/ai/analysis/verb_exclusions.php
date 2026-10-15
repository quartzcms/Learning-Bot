<?php
$isVerb = $dictionaryEntry['cgram'] == 'VER';
$isPresentParticiple = $isVerb && strpos($dictionaryEntry['infover'], 'par:pre;') !== false;

/* A present participle can not be the last word, nor be followed by a verb */
if($isPresentParticiple){
	if($nextWord === null){
		$isWordValid = 0;
	} elseif(isset($analysisData[md5($nextWord)])){
		foreach($analysisData[md5($nextWord)] as $nextWordEntry) {
			if($nextWordEntry['cgram'] == 'VER'){
				$isWordValid = 0;
				break;
			}
		}
	}
}

if($previousWordTypes !== null){
	/* If last word is a verb or an infinitive verb or a past participle and current word is an auxiliary */
	if(
		$dictionaryEntry['cgram'] == 'AUX' &&
		strpos($dictionaryEntry['infover'], 'inf;') === false &&
		(
			in_array('VER', $previousWordTypes) ||
			in_array('VER:inf', $previousWordTypes) ||
			in_array('VER:past', $previousWordTypes)
		) && (
			!isset($questionWords[$wordIndex - 3]) ||
			(isset($keptWordTypes[$questionWords[$wordIndex - 3]]) && !in_array('PRO:int', $keptWordTypes[$questionWords[$wordIndex - 3]]))
		)
	){
		$isWordValid = 0;
	}

	/* If there is two verbs side by side */
	if($isVerb && !$isPresentParticiple && in_array('VER', $previousWordTypes)){
		$isWordValid = 0;
	}
}
