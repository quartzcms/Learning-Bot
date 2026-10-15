<?php
$cgramRules = $syntaxRules[$dictionaryEntry['cgram']] ?? null;

if(!empty($cgramRules['before']) && !empty($cgramRules['after'])){
	/* The current word is only kept if at least one dictionary entry of the next word is accepted after it */
	if($nextWord !== null && isset($analysisData[md5($nextWord)])){
		$neighborMatchFound = 0;
		foreach($analysisData[md5($nextWord)] as $nextWordEntry) {
			if($previousWordTypes !== null){
				foreach($previousWordTypes as $previousTypeCode){
					$exceptionKey = 'exception_before_'.strtolower(str_replace(':', '_', $previousTypeCode));
					if(!empty($cgramRules[$exceptionKey]) && in_array($nextWordEntry['cgram'], $cgramRules[$exceptionKey])){
						$neighborMatchFound = 1;
						break 2;
					}
				}
			}

			if(!in_array($nextWordEntry['cgram'], $cgramRules['after'])){
				$neighborMatchFound = 1;
				break;
			}
		}

		if($neighborMatchFound == 0){
			$isWordValid = 0;
		}
	}

	if(
		isset($cgramRules['empty_after_exception_before']) &&
		($nextWord === null || (!isset($questionWords[$wordIndex + 2]) && strlen($nextWord) == 1))
	){
		if($previousWordTypes !== null && !empty($cgramRules['empty_after_exception_before'])){
			$exceptionMatchFound = 0;
			foreach($cgramRules['empty_after_exception_before'] as $requiredType){
				if(in_array($requiredType, $previousWordTypes)){
					$exceptionMatchFound = 1;
					break;
				}
			}
			if($exceptionMatchFound == 0){
				$isWordValid = 0;
			}
		} else {
			$isWordValid = 0;
		}
	}

	if(isset($cgramRules['not_one_letter']) && strlen($dictionaryEntry['ortho']) == 1){
		$isWordValid = 0;
	}

	if(isset($cgramRules['empty_before']) && $previousWord !== null){
		$isWordValid = 0;
	}

	if($previousWordTypes !== null){
		foreach($cgramRules['before'] as $requiredType){
			if(in_array($requiredType, $previousWordTypes)){
				$isWordValid = 0;
				break;
			}
		}
	}
}
