<?php
/* Function to verify granting in the sentence */
function verify_grammar($fullTag, $keptIndexesByType, $wordPosition, $tagType, $end, $recreatedWords, $wordsByTypeSnapshot) {
	if($tagType != 'other'){
		$previousIndexB = $wordPosition - 1;
		if(isset($recreatedWords[$previousIndexB])){
			$groupTag2 = $recreatedWords[$previousIndexB];
			$groupTag2 = preg_replace('/[0-9]+/', '', $groupTag2);
			$groupTag2 = preg_replace('/[\*]+/', '', $groupTag2);
			$groupTag2 = preg_replace('/[\+]+/', '', $groupTag2);
			$groupTag2 = preg_replace('/[\-]+/', '', $groupTag2);

			if($groupTag2 != 'other'){
				if(
					!empty($wordsByTypeSnapshot[$groupTag2]) &&
					!empty($wordsByTypeSnapshot[$tagType]) &&
					isset($keptIndexesByType[$groupTag2]) &&
					isset($keptIndexesByType[$tagType]) &&
					isset($wordsByTypeSnapshot[$groupTag2][$keptIndexesByType[$groupTag2]]['ortho']) &&
					isset($wordsByTypeSnapshot[$tagType][$keptIndexesByType[$tagType]]['ortho'])
				){
					/* Not accepting 2 words with same wording side by side */
					if($wordsByTypeSnapshot[$groupTag2][$keptIndexesByType[$groupTag2]]['ortho'] == $wordsByTypeSnapshot[$tagType][$keptIndexesByType[$tagType]]['ortho']){
						return 1;
					}
					/* Not accepting a one letter pronoun next to a determinant */
					if(strlen($wordsByTypeSnapshot[$groupTag2][$keptIndexesByType[$groupTag2]]['ortho']) == 1 && $tagType == 'art_def'){
						return 1;
					}
				}
			}
		}
	}

	if(substr_count($fullTag, '*') > 0){
		$tagEndParts = explode('*', $fullTag);
		if(isset($tagEndParts[1])){			
			$tagEndParts = explode('-', end($tagEndParts));
			if(isset($tagEndParts[0])){
				if($tagType != 'other' && strlen($tagEndParts[0]) == 1){
					$previousIndexA = $wordPosition - intval($tagEndParts[0]);
					if(isset($recreatedWords[$previousIndexA])){
						$groupTag = $recreatedWords[$previousIndexA];
						$groupTag = preg_replace('/[0-9]+/', '', $groupTag);
						$groupTag = preg_replace('/[\*]+/', '', $groupTag);
						$groupTag = preg_replace('/[\+]+/', '', $groupTag);
						$groupTag = preg_replace('/[\-]+/', '', $groupTag);

						if($groupTag != 'other'){
							if(
								!empty($wordsByTypeSnapshot[$groupTag]) &&
								!empty($wordsByTypeSnapshot[$tagType]) &&
								isset($keptIndexesByType[$groupTag]) &&
								isset($keptIndexesByType[$tagType]) &&
								isset($wordsByTypeSnapshot[$groupTag][$keptIndexesByType[$groupTag]]['genre']) &&
								isset($wordsByTypeSnapshot[$tagType][$keptIndexesByType[$tagType]]['genre']) &&
								isset($wordsByTypeSnapshot[$groupTag][$keptIndexesByType[$groupTag]]['nombre']) &&
								isset($wordsByTypeSnapshot[$tagType][$keptIndexesByType[$tagType]]['nombre'])
							){
								/* More than 1 star in the syntax pattern means it will grant the kind */
								if(substr_count($fullTag, '*') > 1){
									/* If word kind is granting with the one targeted */
									if($wordsByTypeSnapshot[$groupTag][$keptIndexesByType[$groupTag]]['genre'] != $wordsByTypeSnapshot[$tagType][$keptIndexesByType[$tagType]]['genre']){
										return 1;
									}
								}
								/* If word number is granted with the one targeted */
								if($wordsByTypeSnapshot[$groupTag][$keptIndexesByType[$groupTag]]['nombre'] != $wordsByTypeSnapshot[$tagType][$keptIndexesByType[$tagType]]['nombre']){
									return 1;
								}

							}
						}
					} else {
						return 1;
					}
				}
			}
		}
	}

	if(substr_count($fullTag, '-') > 0){
		$tagEndParts = explode('-', $fullTag);
		if(isset($tagEndParts[1])){
			$auxEnding = $tagEndParts[1];
			if($tagType == 'aux'){
				if(
					!empty($wordsByTypeSnapshot[$tagType]) &&
					isset($keptIndexesByType[$tagType]) &&
					isset($wordsByTypeSnapshot[$tagType][$keptIndexesByType[$tagType]]['lemme'])
				){
					if($auxEnding == '2' && format_word($wordsByTypeSnapshot[$tagType][$keptIndexesByType[$tagType]]['lemme']) != 'avoir'){
						return 1;
					}

					if($auxEnding == '1' && format_word($wordsByTypeSnapshot[$tagType][$keptIndexesByType[$tagType]]['lemme']) != 'etre'){
						return 1;
					}
				}
			}
		}
	}
	return 0;
}
