<?php
/* No verb at all: prepend "il/elle est/sont" built from the first noun */
if($verbCount == 0){
	foreach($rewrittenWords as $currentWord){
		if(isset($currentWord['cgram']) && $currentWord['cgram'] == 'NOM'){
			$subjectPronoun = ($currentWord['genre'] == 'm') ? 'il' : 'elle';

			if($currentWord['nombre'] == 'p'){
				$nounNumber = 'p';
				$subjectPronoun .= 's';
			} else {
				$nounNumber = 's';
			}

			array_unshift($rewrittenWords, array(
				'ortho' => $subjectPronoun,
				'cgram' => 'PRO:per',
				'genre' => (!empty($currentWord['genre']) ? $currentWord['genre'] : 'm'),
				'nombre' => (!empty($currentWord['nombre']) ? $currentWord['nombre'] : 's'),
				'person' => '3'.$nounNumber
			), array(
				'ortho' => ($nounNumber == 's' ? 'est' : 'sont'),
				'cgram' => 'AUX',
				'genre' => 'm',
				'nombre' => $nounNumber,
				'person' => '3'.$nounNumber
			));

			break;
		}
	}
}
