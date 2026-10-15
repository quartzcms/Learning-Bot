<?php
/* Delete last save row matched in memory if the answer is negative */
if($isNegativeSentence == 1){
	$verbLemmas = array(); 
	$likeConditionsByType = array();
	$wordConditionGroups = array();
	$conditionBranches = array();

	foreach($sentenceWords as $wordIndex => $wordInfo) {
		if($wordInfo['cgram'] == 'VER' || $wordInfo['cgram'] == 'VER:past' || $wordInfo['cgram'] == 'VER:inf'){
			$verbLemmas[] = $wordInfo['lemme'];
		}
	}

	/* Find all verbs tense for all the verbs of the user path and put in array */
	if(!empty($verbLemmas)){
		foreach($verbLemmas as $verbIndex => $verbLemma){
			$dictionaryQuerySecondary = mysqli_query($databaseConnection, "SELECT * FROM lexique WHERE lemme = '".addslashes($verbLemma)."'") or die (mysqli_error($databaseConnection));
			if(mysqli_num_rows($dictionaryQuerySecondary) > 0){
				while ($dictionaryEntry = mysqli_fetch_assoc($dictionaryQuerySecondary)) { 
					if($dictionaryEntry['cgram'] == 'VER:inf'){
						$likeConditionsByType[md5($verbLemma)][] = 'ver_inf LIKE \'%"'.addslashes($dictionaryEntry['ortho']).'"%\'';
					} elseif($dictionaryEntry['cgram'] == 'VER:past') { 
						$likeConditionsByType[md5($verbLemma)][] = 'ver_past LIKE \'%"'.addslashes($dictionaryEntry['ortho']).'"%\'';
					} elseif($dictionaryEntry['cgram'] == 'VER') { 
						$likeConditionsByType[md5($verbLemma)][] = 'ver LIKE \'%"'.addslashes($dictionaryEntry['ortho']).'"%\'';
					}
				}
			}
		}
	}

	/* glue each verbs (all tense) to make group with an OR condition for the query */
	if(!empty($likeConditionsByType)){
		foreach($likeConditionsByType as $conditionKey => $typeConditions){
			if(!empty($typeConditions)){
				$wordConditionGroups[] = '('.implode(' COLLATE utf8_bin OR ', $typeConditions).' COLLATE utf8_bin)';
			}
		}
	}

	/* glue all the different main verbs groups together with an AND condition */
	if(!empty($wordConditionGroups)){
		$conditionBranches[] = '('.implode(') AND (', $wordConditionGroups).')';
	}
	/* If one row was inserted before */
	if(use_session('last_inserted_id_'.$botType) && !empty($conditionBranches)){
		$memoryResult = mysqli_query($databaseConnection, "SELECT * FROM ai_memory_".$botType." WHERE id = '" . use_session('last_inserted_id_'.$botType) . "' AND (".implode(') OR (', $conditionBranches).")") or die (mysqli_error($databaseConnection));

		/* If one result is are found using the query of the user path and last ID, and if the word 'no' is found, delete the last memory row inserted */
		if(mysqli_num_rows($memoryResult) > 0){
			mysqli_query($databaseConnection, "DELETE FROM ai_memory_".$botType." WHERE id = '" . use_session('last_inserted_id_'.$botType)."'") or die (mysqli_error($databaseConnection));
			write_session('last_inserted_id_'.$botType, '');
		}
	}
}
