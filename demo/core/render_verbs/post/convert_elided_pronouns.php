<?php
foreach($rewrittenWords as $wordKey => $currentWord){
	if(!isset($currentWord['new']) && isset($currentWord['cgram']) && $currentWord['cgram'] == 'PRO:per:con'){
		$swappedElision = array('m' => 't', 't' => 'm');
		$rewrittenWords[$wordKey]['new'] = $swappedElision[$currentWord['ortho']] ?? $currentWord['ortho'];
	}
}
