<?php
/* VERBS function */
$verbsAndPronouns = renderVerbs($reason, $sentenceWords, $databaseConnection);
$sentenceWords = $verbsAndPronouns['path_array'];

/* CORE */
$coreVariables = array(
	'connexion' => $databaseConnection,
	'action' => $actions,
	'rand' => $randomIndex,
	'type_bot' => $botType,
	'question_array' => $sentenceWords
);
/* Defining a new core class for the learning functions */
$learningCore = new core($coreVariables);
$shortTermLinks = use_session('links_'.$botType);
$hasKnownLinks = 0;
foreach($shortTermLinks as $linkType => $linkWords){
	if(!empty($shortTermLinks[$linkType])){
		$hasKnownLinks = 1;
	}
}

/* Calculus functions */
include('../core/calculus.php');
