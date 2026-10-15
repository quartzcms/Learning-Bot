<?php
/* Walk back from the verb to find an existing subject pronoun */
for($position = max($verbPosition - 1, 0); $position > -1; $position--){
	$currentType = $reindexedWords[$position]['cgram'] ?? null;

	if(in_array($currentType, $conjugatedVerbTypes, true)){
		break;
	}

	if(($currentType == 'PRO:per' || $currentType == 'PRO:dem') && in_array($reindexedWords[$position]['ortho'], $subjectPronounCandidatesWithElision, true)){
		$existingSubjectPronounIndex = $position;
		$existingSubjectPronoun = $reindexedWords[$position]['ortho'];
		break;
	}
}
