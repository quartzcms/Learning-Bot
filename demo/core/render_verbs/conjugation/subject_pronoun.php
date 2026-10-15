<?php
$subjectPronoun = 'il';
$pronounAfterVerbFound = 0;
foreach($sentenceWords as $candidateKey => $candidateWord){
	if(isset($candidateWord['cgram']) && isset($candidateWord['ortho']) && ($candidateWord['cgram'] == 'PRO:per' || $candidateWord['cgram'] == 'PRO:dem') && $existingSubjectPronoun == $candidateWord['ortho']){
		$pronounAfterVerbFound = 1;
		break;
	}
}

if($pronounAfterVerbFound == 0){
	foreach($sentenceWords as $candidateKey => $candidateWord){	
		if($candidateWord['ortho'] == $subjectNoun && isset($candidateWord['genre'])) {
			if($subjectGender == 'm' || $candidateWord['genre'] == 'm'){
				$subjectPronoun = 'il';
			} 
			if($subjectGender == 'f' || $candidateWord['genre'] == 'f'){
				$subjectPronoun = 'elle';
			}
			if($subjectNumber == 's' || $candidateWord['nombre'] == 's'){
				$subjectPronoun .= '';
			}
			if($forcePlural == 1 || $subjectNumber == 'p' || $candidateWord['nombre'] == 'p'){
				$subjectPronoun .= 's';
			}
			break;
		}
	}
} else {
	$subjectPronoun = $existingSubjectPronoun;
}
