<?php
$analysisData = array();
/* For each question word */
foreach($questionWords as $wordIndex => $wordText) {
	/* Query in the dictionnary */			
	$dictionaryQuery = mysqli_query($databaseConnection, "SELECT * FROM lexique WHERE ortho = '" . addslashes(mb_strtolower($wordText, 'UTF-8')) . "' ORDER BY FIND_IN_SET(cgram, 'PRO:int,CON,LIA,ART,ART:def,ART:ind,PRE,PRO:pos,PRO:per,PRO:per:con,ADV,PRO:ind,PRO:rel,PRO:dem,AUX,VER,VER:inf,VER:past,ADJ,ADJ:ind,ADJ:int,ADJ:num,ADJ:pos,ONO,NOM')") or die (mysqli_error($databaseConnection));
	/* If results are found create a new array with index key the question word and all the word possibilities from database */
	if(mysqli_num_rows($dictionaryQuery) > 0){
		while ($dictionaryEntry = mysqli_fetch_assoc($dictionaryQuery)) { 
			$analysisData[md5($wordText)][] = $dictionaryEntry; 
		}
	}
}

foreach($questionWords as $wordIndex => $wordText) {
	if(!isset($analysisData[md5($wordText)]) && isset($questionWords[$wordIndex + 1]) && isset($questionWords[$wordIndex - 1])){
		/* Query in the dictionnary */			
		$dictionaryQuery = mysqli_query($databaseConnection, "SELECT id, ortho, lemme, cgram, genre, nombre, infover, LENGTH(ortho) FROM lexique WHERE ortho LIKE '%" . addslashes(mb_strtolower($wordText, 'UTF-8')) . "%' ORDER BY LENGTH(ortho) ASC LIMIT 1") or die (mysqli_error($databaseConnection));
		/* If results are found create a new array with index key the question word and all the word possibilities from database */
		if(mysqli_num_rows($dictionaryQuery) > 0){
			while ($dictionaryEntry = mysqli_fetch_assoc($dictionaryQuery)) { 
				$analysisData[md5($wordText)][] = $dictionaryEntry; 
			}
		}
	}
}
