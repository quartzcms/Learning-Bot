<?php
$verbCount = 0;
foreach($rewrittenWords as $currentWord){
	if(isset($currentWord['cgram']) && in_array($currentWord['cgram'], $conjugatedVerbTypes, true)){
		$verbCount++;
	}
}
