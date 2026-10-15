<?php
$wordTypeCounts = array_count_values($recreatedWordsNoPlus);
foreach($recreatedWordsNoPlus as $tagIndex => $tagType){
	$subjectPronounIndex = (isset($wordTypeCounts['pro_per']) ? (intval($wordTypeCounts['pro_per']) - 1) : 0);
	$tagTypeNoPlus = $tagType;

	if($tagTypeNoPlus == 'pro_per_con' &&
	isset($wordsByType['pro_per']) && isset($wordsByType['pro_per'][$subjectPronounIndex]) && isset($wordsByType[$tagTypeNoPlus]) && isset($wordsByType[$tagTypeNoPlus][$subjectPronounIndex]) && 
	(($wordsByType['pro_per'][$subjectPronounIndex] == 'tu' && $wordsByType[$tagTypeNoPlus][$subjectPronounIndex] == 's') ||
	(($wordsByType['pro_per'][$subjectPronounIndex] == 'j' || $wordsByType['pro_per'][$subjectPronounIndex] == 'je') && $wordsByType[$tagTypeNoPlus][$subjectPronounIndex] == 's'))){
		$agreementRejected = 1;
	}

	if(isset($wordsByType[$recreatedWordsNoPlus[$tagIndex]])){
		foreach($wordsByType[$recreatedWordsNoPlus[$tagIndex]] as $wordIndex => $wordForm){
			if(isset($wordsByType[$recreatedWordsNoPlus[$tagIndex]]) && isset($recreatedWordsNoPlus[$tagIndex - 1]) && isset($wordsByType[$recreatedWordsNoPlus[$tagIndex - 1]]) && 
			isset($wordsByType[$recreatedWordsNoPlus[$tagIndex - 1]][$wordIndex]) &&
			strlen($wordsByType[$recreatedWordsNoPlus[$tagIndex - 1]][$wordIndex]) == 1 && !in_array(substr(format_word($wordsByType[$recreatedWordsNoPlus[$tagIndex]][$wordIndex]), 0, 1), array('a', 'e', 'i', 'o', 'u', 'y'))){
				$agreementRejected = 1;
			}
		}
	}

	if($tagTypeNoPlus == 'ver' || $tagTypeNoPlus == 'aux'){
		if(!isset($agreementWordsByType[$tagTypeNoPlus]) || !isset($agreementWordsByType[$tagTypeNoPlus][$subjectPronounIndex]) || !isset($agreementWordsByType['pro_per']) || !isset($agreementWordsByType['pro_per'][$subjectPronounIndex]) || $agreementWordsByType[$tagTypeNoPlus][$subjectPronounIndex] != $agreementWordsByType['pro_per'][$subjectPronounIndex]) {
			$agreementRejected = 1;
		}
	}
}
