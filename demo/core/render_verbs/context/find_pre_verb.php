<?php
/* Walk back from the verb to find the subject noun */
for($position = max($verbPosition - 1, 0); $position > -1; $position--){
	$currentType = $reindexedWords[$position]['cgram'] ?? null;
	$previousType = $reindexedWords[$position - 1]['cgram'] ?? null;

	if(in_array($currentType, $conjugatedVerbTypes, true)){
		break;
	}

	if($previousType === 'PRO:per' && $nounDetected == 0){
		$subjectGender = $reindexedWords[$position]['genre'];
		$subjectNumber = $reindexedWords[$position]['nombre'];
		$subjectNoun = $reindexedWords[$position]['ortho'];
		break;
	}

	if($currentType === 'NOM' && $previousType === 'ADJ'){
		$nounDetected = 1;
		$anticipatedSubjectNoun = $reindexedWords[$position]['ortho'];
		$anticipatedSubjectGender = $reindexedWords[$position]['genre'];
		$anticipatedSubjectNumber = $reindexedWords[$position]['nombre'];
		continue;
	}

	if(in_array($currentType, array('NOM', 'ADJ', 'ADJ:dem', 'ADJ:int'), true)){
		$nounDetected = 1;
		if(
			$previousType === null ||
			(
				in_array($previousType, array('CON', 'ART:def', 'ART:ind', 'ADJ:pos', 'PRO:pos'), true) &&
				$reindexedWords[$position - 1]['ortho'] != 'aux' && $reindexedWords[$position - 1]['ortho'] != 'au'
			)
		) {
			if($previousType !== null && $previousType != 'CON'){
				$twoBackType = $reindexedWords[$position - 2]['cgram'] ?? null;
				if(in_array($twoBackType, array('ART:def', 'ART:ind', 'ADJ:pos', 'PRO:pos', 'NOM'), true)){
					continue;
				}
			}

			if(!empty($anticipatedSubjectNoun)){
				$subjectNoun = $anticipatedSubjectNoun;
				$subjectGender = $anticipatedSubjectGender;
				$subjectNumber = $anticipatedSubjectNumber;
			} else {
				$subjectNoun = $reindexedWords[$position]['ortho'];
				$subjectGender = $reindexedWords[$position]['genre'];
				$subjectNumber = $reindexedWords[$position]['nombre'];
			}
			break;
		}
	}
}
