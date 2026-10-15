<?php
foreach($modelTags as $tagIndex => $modelTag){
	if($modelTag != 'question' && $modelTag != 'dot'){
		$tagParts2 = explode('*', $modelTag);
		$tagParts = explode('|', $tagParts2[0]);
		if(count($tagParts) > 1){
			$tagPartsBuilt = array();
			/* If the tag is a multiple choice split the choices and loop in them. */
			foreach($tagParts as $tagPartKey => $tagPart){
				if(!empty($wordsByType[$tagPart])){
					if(!isset($keptIndexes[$tagPart])) { 
						$keptIndexes[$tagPart] = 0; 
					} else {
						$keptIndexes[$tagPart]++;
					}
					/* If the value in the response array is found for first index of the type */
					if(isset($wordsByType[$tagPart][$keptIndexes[$tagPart]])){
						foreach($tagParts as $partIndex => $partName){
							if($partIndex == $tagPartKey){
								$tagPartsBuilt[] = $tagParts[$partIndex];
							}
						}
						/* Store the word and get out of the multiple choice loop */
						break;
					}
				}
			}
			/* If no word are found in the multiple choice get out of the tag loop and set variable to skip this modele */
			if(empty($tagPartsBuilt)){
				$modelRejected = 1;
				break;
			}
			/* If no break, store the tag chosen and found in a new more simple modele array */
			if(substr_count($modelTag, '*') > 0){
				if(substr_count($modelTag, '*') == 1) {
					$recreatedWords[] = $tagPartsBuilt[0]."*".end($tagParts2);
				} else {
					$recreatedWords[] = $tagPartsBuilt[0]."**".end($tagParts2);
				} 
			} else {
				$recreatedWords[] = $tagPartsBuilt[0];
			}

			$recreatedWordsNoPlus[] = str_replace('+', '', $tagPartsBuilt[0]);
		} else {
			/* If the tag is not a multiple choice and is a single word */
			/* If the tag don't contain an optional filter */

			if(strpos($tagParts2[0], '+') === false){
				if(!empty($wordsByType[$tagParts2[0]])){
					if(!isset($keptIndexes[$tagParts2[0]])) { 
						$keptIndexes[$tagParts2[0]] = 0; 
					} else {
						$keptIndexes[$tagParts2[0]]++;
					}

					/* If no value is found in the response array for this type of word break of the tag loop and set variable to skip this modele */
					if(!isset($wordsByType[$tagParts2[0]][$keptIndexes[$tagParts2[0]]])){
						$modelRejected = 1;
						break;
					} else {
						/* If a word is found, store it in a new more simple modele array */
						if(substr_count($modelTag, '*') > 0){
							if(substr_count($modelTag, '*') == 1) {
								$recreatedWords[] = $tagParts[0]."*".end($tagParts2);
							} else {
								$recreatedWords[] = $tagParts[0]."**".end($tagParts2);
							} 
						} else {
							$recreatedWords[] = $tagParts[0];
						}
						$recreatedWordsNoPlus[] = str_replace('+', '', $tagParts[0]);
					}
				} else {
					/* If the type is not found in the response array simply break and set variable to skip this modele */
					$modelRejected = 1;
					break;
				}
			} else {
				/* If tag is optional no validation is needed and simply storing it in the new more simple modele array  */
				if(substr_count($modelTag, '*') > 0){
					if(substr_count($modelTag, '*') == 1) {
						$recreatedWords[] = $tagParts2[0]."*".end($tagParts2);
					} else {
						$recreatedWords[] = $tagParts2[0]."**".end($tagParts2);
					}
				} else {
					$recreatedWords[] = $tagParts2[0];
				}
				$recreatedWordsNoPlus[] = str_replace('+', '', $tagParts2[0]);
			}
		}
	}
}
