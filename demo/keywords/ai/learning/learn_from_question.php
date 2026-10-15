<?php
/* If the sentence contains a posessive adjective or if the last bot response is a question or if the * character is found the chatbot will learn */
if((isset($wordsFoundByType['ADJ:pos']) || isset($wordsFoundByType['PRO:pos']) || $learnTrigger == 'learn' || $hasFreeCard == 1) && $hasKnownLinks == 1 && $isNegativeSentence == 0){
	$order_pro_ver = array();
	/* Create the sentence pattern with each word types */
	$learningCore->createPatterns();
	/* Create the array which will be inserted in the database */
	$learningCore->prepareDataInsert();
	/* Insert data inside the database and return values of the inserted arrays */
	$insertedData = $learningCore->dataInsert($memoryInsert, $wikipediaExtract, $userIp);
	$memoryInsert = $insertedData['memory_insert'];
}
