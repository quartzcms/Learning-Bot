<?php
/* Function to search in an array multidimensional */
function search_multi_array($analysisData, $word, $type){
	if(isset($analysisData[md5($word)])){
		foreach($analysisData[md5($word)] as $entryIndex => $entry){
			if($entry['cgram'] == $type && $entry['ortho'] == $word){
				return true;
			}
		}
	}
	return false;
}
