<?php
$analysisData = '';
$agreementWordsByType = array();
$wordConditionGroups = array();
/* If the chatbot is not learning, loop through all human question data array values and add appropriate conditions */
$acceptedWordTypes = array('nom', 'other', 'adj', 'ver');		
if($memoryInsert != 1 && empty($response_equation)){
	require $ai_module_dir . '/memory/build_query.php';
	require $ai_module_dir . '/memory/fetch_match.php';
}
