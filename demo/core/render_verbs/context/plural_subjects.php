<?php
/* Several coordinated determiners (or nouns) before the verb make the subject plural */
$coordinatedSubjects = array();
if($hasCoordination == 1){
	if(count($precedingDeterminers) > 1){
		$coordinatedSubjects = $precedingDeterminers;
	} elseif(count($precedingNouns) > 1){
		$coordinatedSubjects = $precedingNouns;
	}
}

if(!empty($coordinatedSubjects)){
	$feminineCount = 0;
	$masculineCount = 0;
	foreach($coordinatedSubjects as $collectedWord){
		if($collectedWord['genre'] == 'f'){
			$feminineCount++;
		} else {
			$masculineCount++;
		}
	}

	$subjectGender = ($masculineCount == 0 && $feminineCount != 0) ? 'f' : 'm';
	$subjectNumber = 'p';
}
