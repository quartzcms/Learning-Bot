<?php
//This foreach gets each response type array in each modele value
$sentenceOrderAlt = preg_replace('/[0-9]+/', '', $sentenceOrder);
$sentenceOrderAlt = preg_replace('/[\*]+/', '', $sentenceOrderAlt);
$sentenceOrderAlt = preg_replace('/[\+]+/', '', $sentenceOrderAlt);
$sentenceOrderAlt = preg_replace('/[\-]+/', '', $sentenceOrderAlt);
$o = array();
$isStoring = 0;
$lastChecked = array();
/* 
	-> Example of the array (original pattern array)
	$syntaxModel[] = 'pro_per,aux*1,adv+,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**3,dot';
	$recreatedWords = array(
		'pro_per',
		'aux*1',
		'adv+',
		'lia|art_ind|art_def|adj_num|adj_pos|pro_ind',
		'nom|other**1',
		'adj+**3',
		'dot'
	);
*/
/* Loop through the old syntax pattern tags */
foreach($sentenceOrderAlt as $tagKey => $tagValue){
	if($tagValue != 'question' && $tagValue != 'dot'){
		$tagParts = explode('|', $tagValue);
		if(count($tagParts) > 1){
			/* For multiple choices */
			foreach($tagParts as $tagPart){
				if(!empty($wordsByType[$tagPart])){
					/* If response type is already stored delete first value of response type. */
					if(in_array($tagPart, $lastChecked)){
						if(isset($wordsByType[$tagPart][0])){
							unset($wordsByType[$tagPart][0]);
						}
					}

					/* Reorder response type array */
					$wordsByType[$tagPart] = array_values($wordsByType[$tagPart]);

					/* If there is a value for the response type store it in a new array */
					if(!empty($wordsByType[$tagPart])){
						$reorderedWords[$isStoring] = array();
						$reorderedWords[$isStoring] = $wordsByType[$tagPart];
					}

					$lastChecked[] = $tagPart;

					break;
				}
			}
		} else {
			/* Remove optional filter from tag */
			if(!empty($wordsByType[$tagValue])){
				/* If response type is already stored delete first value of response type. */
				if(in_array($tagValue, $lastChecked)){
					if(isset($wordsByType[$tagValue][0])){
						unset($wordsByType[$tagValue][0]);
					}
				}

				/* Reorder response type array */
				$wordsByType[$tagValue] = array_values($wordsByType[$tagValue]);

				/* If there is a value for the response type store it in a new array */
				if(!empty($wordsByType[$tagValue])){
					$reorderedWords[$isStoring] = array();
					$reorderedWords[$isStoring] = $wordsByType[$tagValue];
				}

				$lastChecked[] = $tagValue;
			}
		}
	} else {
		if($tagValue == 'dot'){
			$reorderedWords[$isStoring] = '.';
		} else {
			$reorderedWords[$isStoring] = '?';
		}
	}
	$isStoring++;
}
