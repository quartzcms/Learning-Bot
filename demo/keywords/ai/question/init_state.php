<?php
/* Including the verbs function */
include('../core/functions.php');
/* Including the AI learning functions */
include('../core/core.php');
/* Defining the necessary variable for the storing of database words */
$wordsFoundByType = array();
$wordsByType = array();
$keptWordsSignature = '';
$keptWordTypes = array();
$keptWordInflections = array();
$hasFreeCard = 0;
