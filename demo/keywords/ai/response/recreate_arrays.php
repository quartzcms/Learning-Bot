<?php
/* Recreate a response array with the human question data array */
$wordsByType = array();
foreach($sentenceWords as $wordIndex => $wordInfo){
	if(isset($wordInfo['cgram'])){
		$typeKey = str_replace(':', '_', mb_strtolower($wordInfo['cgram'], 'UTF-8'));
		if(isset($wordInfo['new'])){
			$wordsByType[$typeKey][] = $wordInfo['new'];
		} else {
			$wordsByType[$typeKey][] = $wordInfo['ortho'];
		}
	}
}
/* Recreate a response array with the human question data array */
$wordsByTypeSnapshot = array();
foreach($sentenceWords as $wordIndex => $wordInfo){
	if(isset($wordInfo['cgram'])){
		$typeKey = str_replace(':', '_', mb_strtolower($wordInfo['cgram'], 'UTF-8'));
		$wordsByTypeSnapshot[$typeKey][] = $wordInfo;
	}
}

$jsonResult = ['temp memory '.use_session('count_response_'.$botType), 'analyse' => array('data' => $analysisData, 'words_found' => $wordsFoundByType, 'links' => use_session('links_'.$botType), 'counter' => use_session('count_response_'.$botType), 'used_id' => implode(', ', use_session('used_id_'.$botType)), 'action' => $actions[$randomIndex], 'question_array' => $questionWords, 'words_kept' => $keptWordsSignature, 'already_said' => $alreadySaidFlag, 'new_sentence' => $alreadySaidSignature)];

$agreementWordsByType = array();			
foreach($sentenceWords as $agreementIndex => $agreementWord){
	if(isset($sentenceWords[$agreementIndex]['person'])){
		if($sentenceWords[$agreementIndex]['cgram'] == 'VER') {
			$agreementWordsByType['ver'][] = $sentenceWords[$agreementIndex]['person'];
		}
		if($sentenceWords[$agreementIndex]['cgram'] == 'AUX') {
			$agreementWordsByType['aux'][] = $sentenceWords[$agreementIndex]['person'];
		}
		if($sentenceWords[$agreementIndex]['cgram'] == 'PRO:per') {
			$agreementWordsByType['pro_per'][] = $sentenceWords[$agreementIndex]['person'];
		}
	}
}
