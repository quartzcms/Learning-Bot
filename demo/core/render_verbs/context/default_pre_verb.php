<?php
if(empty($subjectNoun)){
	$wordCount = count($reindexedWords);
	for($position = (($verbPosition + 1) < $wordCount) ? ($verbPosition + 1) : 0; $position < $wordCount; $position++){
		$currentType = $reindexedWords[$position]['cgram'] ?? null;

		if(in_array($currentType, $conjugatedVerbTypes, true)){
			break;
		}

		if($currentType === 'NOM'){
			$subjectNoun = $reindexedWords[$position]['ortho'];
			$subjectGender = $reindexedWords[$position]['genre'];
			$subjectNumber = $reindexedWords[$position]['nombre'];
			break;
		}
	}
}
