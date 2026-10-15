<?php
/* Without a subject pronoun, only the word right after the verb is checked */
if(empty($existingSubjectPronoun)){
	$wordCount = count($reindexedWords);
	$position = (($verbPosition + 1) < $wordCount) ? ($verbPosition + 1) : 0;
	$currentType = $reindexedWords[$position]['cgram'] ?? null;

	if(
		($currentType === 'PRO:per' || $currentType === 'PRO:dem') &&
		in_array($reindexedWords[$position]['ortho'], $subjectPronounCandidates, true)
	){
		$existingSubjectPronounIndex = $position;
		$existingSubjectPronoun = $reindexedWords[$position]['ortho'];
	}
}
