<?php
/*
	-> Example of recreate array
	$syntaxModel[] = 'pro_per,aux*1,adv+,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**3,dot';
	$recreatedWords = array(
		'pro_per',
		'aux*1',
		'adv+',
		'art_def', <- chosen word
		'nom**1', <- chosen word
		'adj+**3',
		'dot'
	);
*/

foreach($recreatedWords as $tagIndex => $tagName){
	$strippedTag = preg_replace('/[0-9]+/', '', $tagName);
	$strippedTag = preg_replace('/[\*]+/', '', $strippedTag);
	$strippedTag = preg_replace('/[\+]+/', '', $strippedTag);
	$strippedTag = preg_replace('/[\-]+/', '', $strippedTag);

	if(!isset($keptIndexesByType[$strippedTag])) { 
		$keptIndexesByType[$strippedTag] = 0; 
	} else {
		$keptIndexesByType[$strippedTag]++;
		if(!isset($wordsByTypeSnapshot[$strippedTag][$keptIndexesByType[$strippedTag]]) && strpos($tagName, '+') !== false){
			$keptIndexesByType[$strippedTag]--;
		}
	}
	/* Verify granting for each word of the new simple modele array */
	$badConnection = verify_grammar($tagName, $keptIndexesByType, $tagIndex, $strippedTag, '', $recreatedWords, $wordsByTypeSnapshot);

	if($badConnection == 1) {
		$modelFailed = 1;
	}
}

/* If validation has passed with all the granting and response array testing, choose this pattern */

$historyMatchCount = 0;
if(!empty($storedSyntaxHistory)){
	foreach($storedSyntaxHistory as $historyIndex => $historyEntry){
		foreach($wordsByType as $typeKey => $wordValues){
			if(($typeKey == 'ver' || $typeKey == 'nom' || $typeKey == 'aux' || $typeKey == 'ver_past' || $typeKey == 'ver_inf') &&
			$storedSyntaxHistory[$historyIndex]['syntax'] == $syntaxModelValue){
				if(!empty($wordValues) && is_array($wordValues)){
					foreach($wordValues as $wordIndex => $typeWord){
						if(isset($storedSyntaxHistory[$historyIndex]['human'][$typeKey]) && is_array($storedSyntaxHistory[$historyIndex]['human'][$typeKey])){
							if(!empty($storedSyntaxHistory[$historyIndex]['human'][$typeKey]) && in_array($typeWord, $storedSyntaxHistory[$historyIndex]['human'][$typeKey])){
								$historyMatchCount++;
							}
						}
						if(isset($storedSyntaxHistory[$historyIndex]['human'][$typeKey]) && !is_array($storedSyntaxHistory[$historyIndex]['human'][$typeKey])){
							if(!empty($storedSyntaxHistory[$historyIndex]['human'][$typeKey]) && $typeWord == $storedSyntaxHistory[$historyIndex]['human'][$typeKey]){
								$historyMatchCount++;
							}
						}
					}
				}

				if(!empty($wordValues) && !is_array($wordValues)){
					if(isset($storedSyntaxHistory[$historyIndex]['human'][$typeKey]) && is_array($storedSyntaxHistory[$historyIndex]['human'][$typeKey])){
						if(!empty($storedSyntaxHistory[$historyIndex]['human'][$typeKey]) && in_array($wordValues, $storedSyntaxHistory[$historyIndex]['human'][$typeKey])){
							$historyMatchCount++;
						}
					}
					if(isset($storedSyntaxHistory[$historyIndex]['human'][$typeKey]) && !is_array($storedSyntaxHistory[$historyIndex]['human'][$typeKey])){
						if(!empty($storedSyntaxHistory[$historyIndex]['human'][$typeKey]) && $wordValues == $storedSyntaxHistory[$historyIndex]['human'][$typeKey]){
							$historyMatchCount++;
						}
					}
				}
			}
		}
	}
}
