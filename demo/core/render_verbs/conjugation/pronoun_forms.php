<?php
$pronounGender = 'm';
$moodAndTense = array('ind', 'pre');

if(isset($pronounFormTable[$subjectPronoun])){
	list($grammaticalPerson, $pronounNumber, $personalPronoun, $pronounGender) = $pronounFormTable[$subjectPronoun];
	if($personalPronoun === null){
		$personalPronoun = $subjectPronoun;
	}
} else {
	$grammaticalPerson = '3';
	$pronounNumber = 's';
	$personalPronoun = 'il';

	foreach($sentenceWords as $candidateWord){
		if(isset($candidateWord['cgram']) && $candidateWord['cgram'] == "PRO:per" && $candidateWord['ortho'] == $subjectPronoun) {
			if($candidateWord['genre'] == 'f'){
				$personalPronoun = 'elle';
				$pronounGender = 'f';
			} else {
				$personalPronoun = 'il';
			}

			if($candidateWord['nombre'] == 'p'){
				$personalPronoun .= 's';
				$grammaticalPerson .= 'p';
				$pronounNumber = 'p';
			} else {
				$grammaticalPerson .= 's';
				$pronounNumber = 's';
			}
		}
	}

	if($grammaticalPerson == '3'){
		$grammaticalPerson = '3s';
	}

	if(!empty($existingSubjectPronoun) && $reindexedWords[$existingSubjectPronounIndex]['cgram'] == 'PRO:dem') {
		$personalPronoun = '';
	}
}
