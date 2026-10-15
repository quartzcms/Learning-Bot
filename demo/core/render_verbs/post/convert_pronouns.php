<?php
foreach($rewrittenWords as $wordKey => $currentWord){
	if(!isset($currentWord['added']) && !isset($currentWord['existing']) && isset($currentWord['cgram']) && $currentWord['cgram'] == 'PRO:per'){
		$rewrittenWords[$wordKey]['new'] = $pronounSwapMap[$currentWord['ortho']] ?? $currentWord['ortho'];
	}
}
