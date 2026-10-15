<?php
$likeConditionsByType = array();			
foreach($sentenceWords as $wordIndex => $wordInfo){
	$new = array();
	$wordTypeKey = str_replace(':', '_', mb_strtolower($wordInfo['cgram'], 'UTF-8'));
	if(in_array($wordTypeKey, $acceptedWordTypes)){
		if($wordTypeKey == 'ver'){
			$wordTypeKey = $wordTypeKey.md5($wordInfo['ortho']);

			$dictionaryQuerySecondary = mysqli_query($databaseConnection, "SELECT * FROM lexique WHERE lemme = '".$wordInfo['lemme']."'") or die (mysqli_error($databaseConnection));
			if(mysqli_num_rows($dictionaryQuerySecondary) > 0){
				while ($lemmaEntry = mysqli_fetch_assoc($dictionaryQuerySecondary)) { 
					$likeConditionsByType[$wordTypeKey][] = 'keywords LIKE \'%"'.addslashes($lemmaEntry['ortho']).'"%\'';
				}
			}
		} elseif($wordTypeKey == 'nom'){
			$wordTypeKey = $wordTypeKey.md5($wordInfo['ortho']);

			$synonymsQuery = mysqli_query($databaseConnection, "SELECT matches FROM synonymes WHERE keyword = '".addslashes($wordInfo['ortho'])."' AND cgram2 LIKE '%Nom%'") or die (mysqli_error($databaseConnection));
			if(mysqli_num_rows($synonymsQuery) > 0){
				$synonymRow = mysqli_fetch_assoc($synonymsQuery);
				$synonymMatches = explode('|', $synonymRow['matches']);
				foreach($synonymMatches as $synonym){
					$likeConditionsByType[$wordTypeKey][] = 'keywords LIKE \'%"'.addslashes($synonym).'"%\'';
				}
			}
		}

		$likeConditionsByType[$wordTypeKey][] = 'keywords LIKE \'%"'.addslashes($wordInfo['ortho']).'"%\'';
	}
}

/* Adding additional validation for the SQL query in the database, Human database string have to be between 5 more character or 5 less than the user input */
$lengthRangeCondition = 25;			
$questionOrthos = array();
foreach($sentenceWords as $wordIndex => $wordInfo){
	$questionOrthos[] = $wordInfo['ortho'];
}
$questionLength = strlen(implode(' ', $questionOrthos));
if($questionLength > $lengthRangeCondition){
	$minLength = $questionLength - $lengthRangeCondition;
} else {
	$minLength = 0;
}
$maxLength = $questionLength + $lengthRangeCondition;
/******************************/

foreach($likeConditionsByType as $conditionKey => $typeConditions){
	if(!empty($typeConditions)){
		$wordConditionGroups[] = '('.implode(' COLLATE utf8_bin OR ', $typeConditions).' COLLATE utf8_bin)';
	}
}

if(!empty($wordConditionGroups)){
	$usedIdsCondition = '';
	$linksCondition = '';
	if(use_session('used_id_'.$botType)){
		$usedIdsCondition .= ' AND id NOT IN ('.implode(',', use_session('used_id_'.$botType)).')';
	}

	$linkConditions = array();
	/* Adding old short term memory values to the database memory query */
	foreach($acceptedWordTypes as $typeIndex => $linkType) {
		$storedLinks = use_session('links_'.$botType);
		if(isset($storedLinks[$linkType]) && !empty($storedLinks[$linkType])){
			foreach($storedLinks[$linkType] as $linkIndex => $linkWord){
				$linkConditions[] = 'keywords LIKE \'%"'.addslashes($storedLinks[$linkType][$linkIndex]).'"%\'';
			}
		}
	}

	if(!empty($linkConditions)){
		$linksCondition .= ' OR ('.implode(' COLLATE utf8_bin AND ', $linkConditions).' COLLATE utf8_bin)';
	}

	$conditionBranches = array();

	if(!empty($wordConditionGroups)){
		$conditionBranches[] = '('.implode(') AND (', $wordConditionGroups).')';
	}

	$query = 'WHERE (('.implode(') OR (', $conditionBranches).')'.$linksCondition.') AND (pattern LIKE \'%{%\' AND pattern LIKE \'%}%\') AND ip = "'.$userIp.'" AND (LENGTH(human) > '.$minLength.' AND LENGTH(human) < '.$maxLength.')'.$usedIdsCondition;
	$query = $query.' AND (human != "'.$reason.'")';
} else {
	$query = 'WHERE (id = 0) AND ip = "'.$userIp.'"';
	$query = $query.' AND (human != "'.$reason.'")';
}
